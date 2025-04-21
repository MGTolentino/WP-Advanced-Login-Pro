<?php
/**
 * Define la funcionalidad de internacionalización.
 *
 * Carga y define los archivos de internacionalización para este plugin
 * para que esté listo para traducción.
 */
class WP_ALP_i18n {

    /**
     * Carga el dominio de texto del plugin para la traducción.
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'wp-alp',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}