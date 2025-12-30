<?php
/**
 * Template Name: Página de Contacto
 *
 * @package Tecnologia_del_Color
 */

get_header();
?>

<section class="page-header">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-wrapper" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">
            <div class="contact-info">
                <h2><?php _e( 'Información de Contacto', 'tecnologia-del-color' ); ?></h2>
                
                <div class="contact-item">
                    <h3><?php _e( 'Dirección', 'tecnologia-del-color' ); ?></h3>
                    <p><?php echo esc_html( get_theme_mod( 'tdc_address', 'Bernardo de Irigoyen 1717, B1604AFQ Florida, Buenos Aires, Argentina' ) ); ?></p>
                </div>
                
                <div class="contact-item">
                    <h3><?php _e( 'Teléfono', 'tecnologia-del-color' ); ?></h3>
                    <p><?php echo esc_html( get_theme_mod( 'tdc_phone', '(54-11) 4761-2300' ) ); ?></p>
                </div>
                
                <div class="contact-item">
                    <h3><?php _e( 'WhatsApp', 'tecnologia-del-color' ); ?></h3>
                    <p>
                        <a href="https://wa.me/<?php echo esc_attr( get_theme_mod( 'tdc_whatsapp', '5491132832399' ) ); ?>" target="_blank">
                            <?php _e( 'Hablemos por WhatsApp', 'tecnologia-del-color' ); ?>
                        </a>
                    </p>
                </div>
                
                <div class="contact-item">
                    <h3><?php _e( 'Certificaciones', 'tecnologia-del-color' ); ?></h3>
                    <p><?php _e( 'Sistema de Calidad ISO 9001:2015', 'tecnologia-del-color' ); ?></p>
                </div>
                
                <div class="social-links" style="margin-top: 30px;">
                    <a href="https://ar.linkedin.com/company/tecnologiadelcolor" target="_blank" rel="noopener">LinkedIn</a>
                    <a href="https://www.facebook.com/tecnologiadelcolor" target="_blank" rel="noopener">Facebook</a>
                    <a href="https://www.instagram.com/tecnologia.del.color/" target="_blank" rel="noopener">Instagram</a>
                </div>
            </div>
            
            <div class="contact-form">
                <h2><?php _e( 'Envíanos un mensaje', 'tecnologia-del-color' ); ?></h2>
                
                <form id="contact-form" method="post">
                    <p>
                        <label for="contact-name"><?php _e( 'Nombre *', 'tecnologia-del-color' ); ?></label>
                        <input type="text" id="contact-name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </p>
                    
                    <p>
                        <label for="contact-email"><?php _e( 'Email *', 'tecnologia-del-color' ); ?></label>
                        <input type="email" id="contact-email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </p>
                    
                    <p>
                        <label for="contact-phone"><?php _e( 'Teléfono', 'tecnologia-del-color' ); ?></label>
                        <input type="tel" id="contact-phone" name="phone" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    </p>
                    
                    <p>
                        <label for="contact-message"><?php _e( 'Mensaje *', 'tecnologia-del-color' ); ?></label>
                        <textarea id="contact-message" name="message" rows="6" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                    </p>
                    
                    <p>
                        <button type="submit" class="btn btn-primary"><?php _e( 'Enviar Mensaje', 'tecnologia-del-color' ); ?></button>
                    </p>
                    
                    <div id="form-message" style="margin-top: 20px;"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
