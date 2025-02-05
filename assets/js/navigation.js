/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens.
 */
( function() {
    const siteNavigation = document.getElementById( 'site-navigation' );
    const button = siteNavigation.querySelector( '.menu-toggle' );

    // Return early if the navigation doesn't exist.
    if ( ! siteNavigation ) {
        return;
    }

    const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

    // Return early if the menu doesn't exist.
    if ( ! menu ) {
        button.style.display = 'none';
        return;
    }

    if ( ! menu.classList.contains( 'nav-menu' ) ) {
        menu.classList.add( 'nav-menu' );
    }

    // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
    button.addEventListener( 'click', function() {
        siteNavigation.classList.toggle( 'toggled' );

        if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
            button.setAttribute( 'aria-expanded', 'false' );
        } else {
            button.setAttribute( 'aria-expanded', 'true' );
        }
    } );

    // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
    document.addEventListener( 'click', function( event ) {
        const isClickInside = siteNavigation.contains( event.target );

        if ( ! isClickInside ) {
            siteNavigation.classList.remove( 'toggled' );
            button.setAttribute( 'aria-expanded', 'false' );
        }
    } );

    // Close menu when user presses Escape key
    document.addEventListener( 'keydown', function( event ) {
        if ( event.key === 'Escape' ) {
            siteNavigation.classList.remove( 'toggled' );
            button.setAttribute( 'aria-expanded', 'false' );
        }
    } );
} )();