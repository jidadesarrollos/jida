# Ajustes para pasar aplicaciones en versión 0.6 a la actual

### Eliminar constantes:

- URL_HTDOCS
- URL_HTDOCS_ADMIN
- URL_CSS 
- URL_BASE
- URL_APP
- URL_APP_PUBLICA
- URL_HTDOCS_JADMIN
- URL_HTDOCS_TEMA

En su lugar usar Configuracion::URL_BASE o Configuracion::URL_ABSOLUTA.
