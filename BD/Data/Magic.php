<?php

namespace Jida\BD\Data;

Trait Magic {


    /**
     * Realiza llamado a los objetos de relacion existentes
     * @method __call
     * @method
     *
     * @deprecated
     */
    function __call($rel, $campos) {

        //chequear esto.
        if ($rel == 'initBD') {
            $this->initBD($campos[0]);

            return true;
        };
        if (method_exists($this, $rel)) {

        }
        else {

            $class = ucfirst($this->_obtenerSingular($rel));

            if (property_exists($this, $rel) and !in_array($rel, $this->tieneMuchos)) {
                return $this->$rel;
            }

            if (in_array($class, $this->tieneMuchos)) {

                $obj = new $class(null, 1);
                if (method_exists($obj, 'consulta')) {
                    $pk = $this->pk;

                    $obj->$pk = $this->$pk;
                    if (!empty($this->$pk))
                        return $obj->consultaSola($campos)->filtro([$this->pk => $this->$pk]);
                    else
                        return $obj;
                }
            }
            else if (in_array($class, $this->tieneUno) or array_key_exists($class, $this->tieneUno)
                     or in_array($class,
                    $this->tieneMuchos)) {

                $obj = new $class(null, 1);
                if (method_exists($obj, 'consulta')) {
                    if (!in_array($class, $this->tieneUno)) {
                        $pkRelacion = $this->tieneUno[$class]['fk'];
                    }
                    else {
                        $pkRelacion = $obj->__get('pk');
                    }
                    // se obtiene el campo de clave primaria del objeto relacion
                    Debug::string($this->{$pkRelacion}, 1);
                    exit;
                    if (!empty($this->{$pkRelacion}))
                        $this->$rel = $obj->instanciar($this->$pkRelacion);
                    else
                        $this->$rel = $obj;

                    return $this->$rel;
                }
            }
            else {

                throw new Exception("El objeto solicitado como relacion no existe $class $rel", 1);

            }
        }

    }

}