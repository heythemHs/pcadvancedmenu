/**
 * PC Advanced Menu - Frontend JavaScript
 * Based on CodePen multilevel hamburger menu
 */

(function() {
  'use strict';

  // Wait for DOM to be ready
  document.addEventListener('DOMContentLoaded', function() {

    // Cache selectors
    var menuTrigger = document.querySelector('.js-menuToggle');
    var topNav = document.querySelector('.js-topPushNav');
    var openLevelButtons = document.querySelectorAll('.js-openLevel');
    var closeLevelButtons = document.querySelectorAll('.js-closeLevel');
    var closeLevelTop = document.querySelector('.js-closeLevelTop');
    var screenOverlay = document.querySelector('.screen');

    if (!menuTrigger || !topNav) {
      return; // Exit if required elements don't exist
    }

    /**
     * Open push navigation menu
     */
    function openPushNav() {
      topNav.classList.add('isOpen');
      document.body.classList.add('pushNavIsOpen');
    }

    /**
     * Close push navigation menu
     */
    function closePushNav() {
      topNav.classList.remove('isOpen');

      // Close all open submenus
      var openSubmenus = document.querySelectorAll('.js-pushNavLevel.isOpen');
      openSubmenus.forEach(function(submenu) {
        submenu.classList.remove('isOpen');
      });

      document.body.classList.remove('pushNavIsOpen');
    }

    /**
     * Toggle menu on burger click
     */
    menuTrigger.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      if (topNav.classList.contains('isOpen')) {
        closePushNav();
      } else {
        openPushNav();
      }
    });

    /**
     * Open submenu level
     */
    openLevelButtons.forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Find the next sibling ul element
        var nextLevel = this.nextElementSibling;
        while (nextLevel && nextLevel.tagName !== 'UL') {
          nextLevel = nextLevel.nextElementSibling;
        }

        if (nextLevel && nextLevel.classList.contains('js-pushNavLevel')) {
          nextLevel.classList.add('isOpen');
        }
      });
    });

    /**
     * Close submenu level
     */
    closeLevelButtons.forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Find the closest parent with class js-pushNavLevel
        var currentLevel = this.closest('.js-pushNavLevel');
        if (currentLevel) {
          currentLevel.classList.remove('isOpen');
        }
      });
    });

    /**
     * Close all levels (top close button)
     */
    if (closeLevelTop) {
      closeLevelTop.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        closePushNav();
      });
    }

    /**
     * Close menu when clicking on screen overlay
     */
    if (screenOverlay) {
      screenOverlay.addEventListener('click', function() {
        closePushNav();
      });
    }

    /**
     * Close menu on ESC key
     */
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' || e.keyCode === 27) {
        if (topNav.classList.contains('isOpen')) {
          closePushNav();
        }
      }
    });

    /**
     * Prevent body scroll when menu is open
     */
    function preventBodyScroll(e) {
      if (document.body.classList.contains('pushNavIsOpen')) {
        var target = e.target;
        var isMenu = target.closest('.pushNav');

        if (!isMenu) {
          e.preventDefault();
        }
      }
    }

    // Add touch event listeners for mobile
    if ('ontouchstart' in window) {
      document.addEventListener('touchmove', preventBodyScroll, { passive: false });
    }

    /**
     * Handle window resize
     */
    var resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        // Close menu if window is resized while open
        if (topNav.classList.contains('isOpen')) {
          closePushNav();
        }
      }, 250);
    });

  });

  /**
   * jQuery version for backward compatibility
   * Only runs if jQuery is available
   */
  if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function($) {
      var $menuTrigger = $('.js-menuToggle');
      var $topNav = $('.js-topPushNav');
      var $openLevel = $('.js-openLevel');
      var $closeLevel = $('.js-closeLevel');
      var $closeLevelTop = $('.js-closeLevelTop');
      var $navLevel = $('.js-pushNavLevel');
      var $screen = $('.screen');

      if (!$menuTrigger.length || !$topNav.length) {
        return;
      }

      function openPushNav() {
        $topNav.addClass('isOpen');
        $('body').addClass('pushNavIsOpen');
      }

      function closePushNav() {
        $topNav.removeClass('isOpen');
        $openLevel.siblings($navLevel).removeClass('isOpen');
        $('body').removeClass('pushNavIsOpen');
      }

      $menuTrigger.on('click touchstart', function(e) {
        e.preventDefault();
        if ($topNav.hasClass('isOpen')) {
          closePushNav();
        } else {
          openPushNav();
        }
      });

      $openLevel.on('click touchstart', function(e) {
        e.preventDefault();
        $(this).next($navLevel).addClass('isOpen');
      });

      $closeLevel.on('click touchstart', function(e) {
        e.preventDefault();
        $(this).closest($navLevel).removeClass('isOpen');
      });

      $closeLevelTop.on('click touchstart', function(e) {
        e.preventDefault();
        closePushNav();
      });

      $screen.on('click', function() {
        closePushNav();
      });

      $(document).on('keydown', function(e) {
        if (e.keyCode === 27 && $topNav.hasClass('isOpen')) {
          closePushNav();
        }
      });
    });
  }

})();
