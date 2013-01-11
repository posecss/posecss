# ABOUT
Pose is a lightweight php powered css engine that allows you to use variables, functions, includes and more directly in your css files. It can merge multiple stylesheets into one css file and even minify and cache it for you. Pose aims to make your stylesheets faster, smaller, more dynamic and easier to code.

Pose outputs 100% pure css, and uses standard .css files, so there's no need for proprietary file extensions or anything that wont play nice with your current stylesheets. Installation is a snap as well, simply copy & paste a couple files to your sites css directory and your ready go. Pose is also extremely easy to customize. Most of it's internal settings can be easily accessed & modified from a well commented configuration file.

On top of pose's impressive css processing abilities, it also includes a powetful plugin system that lets you easily extend Pose with additional functionality to your sites and to share with the community. Some of the default plugins are a set of css helpers that can speed up your development time, a framework plugin that allows you to automatically include a number css frameworks (52framework, 960gs, blueprint & bluetrip currently), or reset plugin that will quickly add one of several popular reset stylesheets (YUI2, YUI3, Eric Meyer and HTML5Doctor.com).

# OVERVIEW

### VARIABLES
If you've ever wished you could just set a value for something once and then use it everywhere in your stylesheets (a color for example), then variables are what you've been wishing for. You can define a value once and have it available throughout your entire stylesheet. And if you ever need to update something, you'll only have to make the change in one place. For more, jump to the variables on the docs page. Here's a quick example...

  /* Define some variables */
  @primary_color {#3090d8}
  @dark_grey {#44525c}
  
  /* Now let's use them! */
  h1 {color:@primary_color!;}
  p {color:@dark_grey!;}
  a {color:@primary_color!;}


### FUNCTIONS
Functions in Pose work a lot like like they do in PHP or Javascript. Essentially, a function is a block of code that can be reused throughout your stylesheets. And much like php, you can pass variables to your functions to make them even more modular. Jump to the functions section of the docs.

  /* First, we define a function */
  function round(@radius) {
    border-radius: @radius;
    -webkit-border-radius: @radius;
    -moz-border-radius: @radius;
  }
  
  /* And now we can use it! */
  div.round {
    @round(5px);
  }
  
  h1 {
    font-size: 30px;
    @round(10px);
  }


### PUTTING IT ALL TOGETHER
Here's a very quick example that sets up a few variables & functions and puts them into practical use. Everything between the <define> and </define> tags will be our variables & functions. Define tags are completely optional, but anything within them will be excluded from the final output, so they're useful for defining things like variables & functions that you want to use in your stylesheet, but don't actually need to be sent to the browser. These definitions could easily be created in a separate file and included using pose's include function. But, for the sake of simplicity, I'll write everything in this example as one file.

  <define>
  @img {http://yoursite.com/images}  /* holds the location of our image directory. */
  @blue {#3090d8} /* This variable holds the color of a specific shade of blue (#3090d8) */
  
  /* Now let's create a "function" with a variable radius to round corners */
  function round(@radius) {
    border-radius: @radius;
    -moz-border-radius: @radius;
  }
  
  /* This function will rotate an element by an amount passed to the !amount variable. */
  function rotate(@amount) {
    -moz-transform: rotate(@amount);
    -webkit-transform: rotate(@amount);
    -o-transform: rotate(@amount);
    transform: rotate(@amount);
  }
  <define>
  
  
  /* Now, here's an example of how these variables & functions can be put into action. */
  #div.styles {
    color:@blue!;
    background-image:url(@img!/background.png)
    @rotate(8deg)
    @round(10px)
  }


The above code will be sent to the browser like this:

  #div.styles {
    color: #3090d8;
    background-image:url(http://yoursite.com/images/background.png)
    -moz-transform: rotate(8deg);
    -webkit-transform: rotate(8deg);
    -o-transform: rotate(8deg);
    transform: rotate(8deg);
    border-radius: 10px;
    -moz-border-radius: 10px;
  }




# Documentation

### [Introduction to Pose](http://github.com/posecss/posecss/wiki/1.-Introduction-to-Pose)
+  About
+  Browser Compatibility
+  Server Compatibility


### [Basic Usage](http://github.com/posecss/posecss/wiki/2.-Basic-Usage)
+  Variables
+  Functions
+  Includes
+  Definitions


### [Installation + Configuration](http://github.com/posecss/posecss/wiki/3.-Installation-%26-Configuration)
+  Server Configuration
+  Installation Options
+  Configuration Options

### [Plugins](http://github.com/posecss/posecss/wiki/4.-Plugins)
+  Reset File
+  CSS Frameworks
+  Helpers

### [Optimization Tips](http://github.com/posecss/posecss/wiki/5.-Optimization-Tips)
+  Caching
+  Minification
+  Save to File

### [Experimental Features](http://github.com/posecss/posecss/wiki/6.-Experimental-Features)
+  Browser Filtering


#### [Version History](http://github.com/posecss/posecss/wiki/Version-History)
