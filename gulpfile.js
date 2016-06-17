var elixir = require('laravel-elixir');

elixir(function(mix) {
    mix.scripts([
              'app.js',
                'config.js',
                'controllers/*.js',
                'modules/*.js',
                'directives/*.js',
                'services/*.js',
                'menu.js'
              ], 'public/js/app.js');

    mix.scripts([
            '../../../node_modules/angular-bootstrap-switch/dist/angular-bootstrap-switch.js',
            '../../../node_modules/moment/moment.js',
            '../../../node_modules/string-mask/src/string-mask.js',
            '../../../node_modules/sweetalert/dist/sweetalert-dev.js',
            '../../../node_modules/ng-file-upload/dist/ng-file-upload-shim.js',
            '../../../node_modules/ng-file-upload/dist/ng-file-upload.js',
              'dist/*.js',
      ],'public/js/dist.js');

    mix.copy([
      'resources/assets/js/scripts/'
      ],'public/js/scripts/');
});
