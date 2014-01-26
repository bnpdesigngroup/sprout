<?php

/**
 * Question: Where the heck are all the page files?
 * 
 * Short Answer: In templates/query
 * 
 * Long Answer: This theme is intended to tackle one problem which comes up
 * over and over again, and thats extending a theme without locking yourself in.
 * Whenever you modify a theme, you commit yourself to manually migrating it 
 * every time there is an update. The more you try to do with it, the more
 * pain there is down the road. 
 * 
 * One solution built right into WordPress is to create a child theme and then
 * make all your changes there. While that looks nice on paper, there are several 
 * problems with this approach:
 * - You write more. 
 *     This is particularly the case with styles. It is much easier to create
 *     styles than to override them. You end up playing whack-a-mole with your 
 *     browser.
 * - You take the bad with the good. 
 *     Most themes do stuff you love and stuff you hate, and rarely provide a way 
 *     to cleanly remove the stuff you hate. So you are left with hanging features 
 *     cluttering the UI.
 * - You forget what you did.
 *     Every theme has its quirks, and once you've left the code for a month,
 *     you'll come back and lose a whole day wondering why you needed to add this
 *     filter here and that action there. The more themes you use, the worse
 *     this gets.
 * - You can't reuse what you remember.
 *     Whatever you wrote most likely overrode a very specific functionality in 
 *     a very specific context. Theres nothing you can really just copy+paste,
 *     unless you use the exact same theme.
 * - The end code looks hacky.
 *     Child themes are literally an afterthought. Their main method of altering
 *     functionality is frantically adding and removing filters all over the
 *     place.
 *
 * A better alternative is to use a starter theme like Roots (http://roots.io/starter-theme/).
 * Roots is awesome because it allows you to override core functionality from a 
 * child theme.
 * 
 * This is also a starter theme. It actually started out as a modification of Roots.
 * It extends on Roots in a number of ways:
 * - OOP
 *     Sprout is just a collection of modules that do things. Every module focuses
 *     on doing its own thing, which makes it really easy to modify or remove a module 
 *     you dont like without interfering with the others. Conversely, it doesn't take 
 *     much to add a module to Sprout and have it treated as if it was part of the core.
 * - GroundworkCSS (http://groundworkcss.github.io/)
 *     This sassy framework allows you to rapidly design pages and control your overall
 *     look at a high level. Unlike Bootstrap, it can make use of ems by setting a 
 *     couple of values.
 * - OptionTree (https://github.com/valendesigns/option-tree)
 *     WordPress has an alright settings API, but it often feels like reinventing
 *     with each new screen. OptionTree extracts away the details and allows you to
 *     make a quick, functional and beautiful theme option screen. The whole thing
 *     is encapsulated into an "options" module.
 * 
 * With all the package and config files, the root directory felt a little messy. So I
 * tweaked the template module to load main templates from "templates/query". If you 
 * don't like that, then you can copy/paste the module into your child theme and 
 * tweak it.
 */

?>