var elixir = require('laravel-elixir');

elixir(function (mix) {

  mix.browserify('main.js');

  mix.scripts([
    '../../../node_modules/jquery/dist/jquery.js',
    '../../../node_modules/moment/moment.js',
    '../../../node_modules/materialize-css/dist/js/materialize.js'
  ], 'public/js/dist.js');

  mix.styles([
    "../../../node_modules/materialize-css/dist/css/materialize.css",
    "main.css"
  ],'public/css/dist.css');

  mix.copy('node_modules/materialize-css/dist/fonts', 'public/fonts');

});
