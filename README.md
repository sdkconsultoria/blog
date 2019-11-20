Accesible Investment
====


Instalación
------------
clonar el repositorio

```
git clone ssh://git.ingiga.com:40220/git/ernesto-osorno/accesible-investment
```

Copiar el contenido de la plantilla del frontend `themeforest-v5jR6AWi-ideapress-crowdfunding-fundraising-html-template/ideapress placeholder/`

```
accesible-investment/public/template
```

Copiar el contenido de la plantilla del backend`modern-admin-clean-bootstrap-4-dashboard-html-template-3.0/modern-admin/app-assets/` a la ruta

```
accesible-investment/public/app-assets
```

Generar la configuracion local ejecutando

```
cp .env.example .env
```

Ejecutar el comando

```
php artisan key:generate
```

Ejecutar el servidor

```
php artisan serv
```
