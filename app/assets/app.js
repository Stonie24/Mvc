// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import hello from './js/hello.js';
import './styles/app.css';

console.log(hello())
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
