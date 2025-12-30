jQuery(document).ready(function($) {
    'use strict';
    
    // Mobile menu toggle
    $('#menu-toggle').on('click', function() {
        $('.main-navigation').toggleClass('active');
        $(this).attr('aria-expanded', function(index, attr) {
            return attr === 'true' ? 'false' : 'true';
        });
    });
    
    // Mobile submenu toggle
    $('.main-navigation .menu-item-has-children > a').on('click', function(e) {
        if ($(window).width() <= 768) {
            e.preventDefault();
            $(this).parent().toggleClass('active');
            $(this).next('.sub-menu').slideToggle(300);
        }
    });
    
    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.main-navigation, #menu-toggle').length) {
            $('.main-navigation').removeClass('active');
            $('#menu-toggle').attr('aria-expanded', 'false');
        }
    });
    
    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // Contact form submission
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $message = $('#form-message');
        var $submitBtn = $form.find('button[type="submit"]');
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text('Enviando...');
        
        $.ajax({
            url: tdcData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'tdc_contact_form',
                nonce: tdcData.nonce,
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                phone: $form.find('[name="phone"]').val(),
                message: $form.find('[name="message"]').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.removeClass('error').addClass('success').text(response.data.message);
                    $form[0].reset();
                } else {
                    $message.removeClass('success').addClass('error').text(response.data.message);
                }
                
                // Re-enable submit button
                $submitBtn.prop('disabled', false).text('Enviar Mensaje');
                
                // Hide message after 5 seconds
                setTimeout(function() {
                    $message.fadeOut(function() {
                        $(this).removeClass('success error').hide();
                    });
                }, 5000);
            },
            error: function() {
                $message.removeClass('success').addClass('error').text('Error al enviar el mensaje. Por favor intente nuevamente.');
                $submitBtn.prop('disabled', false).text('Enviar Mensaje');
            }
        });
    });
    
    // Animate elements on scroll
    function animateOnScroll() {
        $('.service-card, .client-logo, .about-content').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animated');
            }
        });
    }
    
    // Initial check
    animateOnScroll();
    
    // Check on scroll
    $(window).on('scroll', function() {
        animateOnScroll();
    });
    
    // Add sticky header effect
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 100) {
            $('.site-header').addClass('scrolled');
        } else {
            $('.site-header').removeClass('scrolled');
        }
    });
});
