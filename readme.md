Sprout Theme
====
A good theme should adapt to your project, not the other way around.

Sprout is a WordPress theme based on [Roots](https://github.com/roots/roots) & [GroundworkCSS](http://groundworkcss.github.io) that will help you make sustainable themes.

Typically you drop in a starter theme and begin directly modifying it to fit your requirements. While fast, this
makes updating the theme down the road difficult, and limits how many variations of the theme you can manage.

Sprout is different from other themes in that it organizes its core logic into modules which any child theme 
can extend or override. The goal of Sprout is to separate your project from the minutiae that form its foundation,
so that you can branch out and create a host of themes without the host of headaches. 

Using Sprout
----
* Drop this theme into your WordPress site, along with a Leaf child theme of your choice.
* From the command line, navigate to your child theme directory and run `npm install`, followed by `grunt watch`.
* Begin developing.

Advantages of Sprout
----
* Modular design - Sprout encapsulates functionality into organized modules which can easily be extended or overridden.
* DRY templates - Sprout wraps templates so you don't have to redefine headers and footers for every template, while providing you the freedom to radically customize the look and feel of any given page.
* OptionTree Integration - Sprout uses [OptionTree](https://github.com/valendesigns/option-tree) to create and manage its settings.
* Grunt integration - Sprout runs on [Grunt](http://gruntjs.com/), which handles everything from minifying js to [blessing](http://blesscss.com/) css.
* SCSS framework - The Leaf themes come configured to import Sprout's css framework, so you can start writing sassy css with the power of GroundworkCSS right away.
* WordPress improvements, including:
  * Clean head
  * Html5 markup
  * Short root relative URLs
  * Advanced script handling (fallbacks, conditions)
* Variety - By separating project-specific logic from the core theme, Sprout enables you to rapidly design and experiment without locking yourself into any one implementation.

Leaves
----
* Standard Leaf - The basic child theme that shows you how to extend Sprout

See all the leaves at the [garden](https://github.com/bnpdesigngroup/sprout-garden).