/**
 * SIPORA Smooth Page Transitions
 * Reduced-motion version: keep navigation immediate and scrolling non-animated.
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        animationDuration: 0,
        scrollDuration: 0,
        excludeSelectors: [
            'a[href*="logout"]',
            'a[target="_blank"]',
            'a[download]',
            'a[data-no-transition]'
        ],
        onTransitionStart: null,
        onTransitionEnd: null
    };

    // State
    /**
     * Initialize smooth transitions
     */
    function init() {
        document.body.classList.add('page-ready');

        console.log('✓ SIPORA Smooth Transitions initialized in reduced-motion mode');
    }

    /**
     * Handle link clicks for smooth transitions
     */
    function handleLinkClick(e) {
        return;
    }

    /**
     * Check if link should skip smooth transition
     */
    function shouldSkipTransition(link) {
        return true;
    }

    /**
     * Check if URL is same domain
     */
    function isSameDomain(url) {
        try {
            const urlObj = new URL(url, window.location.href);
            return urlObj.origin === window.location.origin;
        } catch {
            return false;
        }
    }

    /**
     * Navigate to a new page with smooth transition
     */
    function navigateTo(url) {
        window.location.href = url;
    }

    /**
     * Setup smooth scroll behavior with hardware acceleration
     */
    function setupSmoothScroll() {
        return;
    }

    /**
     * Smooth scroll to element
     */
    function smoothScrollTo(element, duration = config.scrollDuration) {
        if (!element) return;
        element.scrollIntoView({ behavior: 'auto', block: 'start' });
    }

    /**
     * Enhanced version - Alternative lightweight approach
     * This is used if you prefer not to use full page fetch
     */
    window.SmoothTransitions = {
        smoothScrollTo: smoothScrollTo,
        navigateTo: navigateTo,
        config: config
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

/**
 * Additional utility: Smooth scroll anchor navigation
 * This provides smooth scrolling for same-page anchor links
 */
document.addEventListener('DOMContentLoaded', function() {});
