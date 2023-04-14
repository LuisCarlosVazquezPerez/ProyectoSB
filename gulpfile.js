//GULP NOS DA CIERTAS FUNCIONES
const { src, dest, watch } = require("gulp");
//CSS
const sass = require("gulp-sass")(require("sass"));
const plumber = require('gulp-plumber');

function css(done) {
   src("src/scss/**/*.scss") //Identificar el archivo de SASS
       .pipe(plumber())
       .pipe(sass()) //Compilarlo
       .pipe(dest("build/css")) //Almacenarla en el disco duro

   done(); //es un callback que avisa a gulp cuando llegamos al final
}

function dev(done){
   watch("src/scss/**/*.scss", css); //Watch es para que siga ejecutandose
   done();
}

exports.css = css;
exports.dev = dev;