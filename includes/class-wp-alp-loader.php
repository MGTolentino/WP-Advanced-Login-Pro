<?php
/**
 * Registra todos los actions y filters para el plugin.
 *
 * Mantiene una lista de todos los hooks que están registrados
 * y los ejecuta cuando es apropiado.
 */
class WP_ALP_Loader {

    /**
     * Array de acciones registradas con WordPress.
     */
    protected $actions;

    /**
     * Array de filtros registrados con WordPress.
     */
    protected $filters;

    /**
     * Inicializa las colecciones.
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }

    /**
     * Añade una acción a la colección para ser registrada con WordPress.
     *
     * @param string $hook          El nombre del hook de WordPress.
     * @param object $component     Una referencia al objeto instanciado.
     * @param string $callback      El nombre de la función/método a ejecutar.
     * @param int    $priority      La prioridad en la cual el método debe ser ejecutado.
     * @param int    $accepted_args El número de argumentos que se deben pasar al método.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Añade un filtro a la colección para ser registrado con WordPress.
     *
     * @param string $hook          El nombre del hook de WordPress.
     * @param object $component     Una referencia al objeto instanciado.
     * @param string $callback      El nombre de la función/método a ejecutar.
     * @param int    $priority      La prioridad en la cual el método debe ser ejecutado.
     * @param int    $accepted_args El número de argumentos que se deben pasar al método.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Utilidad para registrar un hook.
     *
     * @param array  $hooks         La colección de hooks existentes.
     * @param string $hook          El nombre del hook de WordPress.
     * @param object $component     Una referencia al objeto instanciado.
     * @param string $callback      El nombre de la función/método a ejecutar.
     * @param int    $priority      La prioridad en la cual el método debe ser ejecutado.
     * @param int    $accepted_args El número de argumentos que se deben pasar al método.
     *
     * @return array La colección de hooks modificada.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Registra los filtros y acciones con WordPress.
     */
    public function run() {
        // Registrar filtros
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        // Registrar acciones
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }
    }
}