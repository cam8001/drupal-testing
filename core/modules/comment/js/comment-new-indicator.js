/**
 * Attaches behaviors for the Comment module's "new" indicator.
 *
 * May only be loaded for authenticated users, with the History module enabled.
 */
(function ($, Drupal, window) {

"use strict";

/**
 * Render "new" comment indicators wherever necessary.
 */
Drupal.behaviors.commentNewIndicator = {
  attach: function (context) {
    // Collect all "new" comment indicator placeholders (and their corresponding
    // node IDs) newer than 30 days ago that have not already been read after
    // their last comment timestamp.
    var nodeIDs = [];
    var $placeholders = $(context)
      .find('[data-comment-timestamp]')
      .once('history')
      .filter(function () {
        var $placeholder = $(this);
        var commentTimestamp = parseInt($placeholder.attr('data-comment-timestamp'), 10);
        var nodeID = $placeholder.closest('[data-history-node-id]').attr('data-history-node-id');
        if (Drupal.history.needsServerCheck(nodeID, commentTimestamp)) {
          nodeIDs.push(nodeID);
          return true;
        }
        else {
          return false;
        }
      });

    if ($placeholders.length === 0) {
      return;
    }

    // Fetch the node read timestamps from the server.
    Drupal.history.fetchTimestamps(nodeIDs, function () {
      processCommentNewIndicators($placeholders);
    });
  }
};

function processCommentNewIndicators($placeholders) {
  var isFirstNewComment = true;
  var newCommentString = Drupal.t('new');
  var $placeholder;

  $placeholders.each(function (index, placeholder) {
    $placeholder = $(placeholder);
    var timestamp = parseInt($placeholder.attr('data-comment-timestamp'), 10);
    var $node = $placeholder.closest('[data-history-node-id]');
    var nodeID = $node.attr('data-history-node-id');
    var lastViewTimestamp = Drupal.history.getLastRead(nodeID);

    if (timestamp > lastViewTimestamp) {
      // Turn the placeholder into an actual "new" indicator.
      var $comment = $(placeholder)
        .removeClass('hidden')
        .text(newCommentString)
        .closest('.comment')
        // Add 'new' class to the comment, so it can be styled.
        .addClass('new');

      // Insert "new" anchor just before the "comment-<cid>" anchor if
      // this is the first new comment in the DOM.
      if (isFirstNewComment) {
        isFirstNewComment = false;
        $comment.prev().before('<a id="new" />');
        // If the URL points to the first new comment, then scroll to that
        // comment.
        if (window.location.hash === '#new') {
          window.scrollTo(0, $comment.offset().top - Drupal.displace().top);
        }
      }
    }
  });
}

})(jQuery, Drupal, window);
