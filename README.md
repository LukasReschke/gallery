# Gallery Plus [BETA] [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/interfasys/galleryplus/badges/quality-score.png?b=stable7)](https://scrutinizer-ci.com/g/interfasys/galleryplus/?branch=stable7) [![Code Climate](https://codeclimate.com/github/interfasys/galleryplus/badges/gpa.svg)](https://codeclimate.com/github/interfasys/galleryplus) [![Build Status](https://travis-ci.org/interfasys/galleryplus.svg?branch=stable7)](https://travis-ci.org/interfasys/galleryplus)
Media gallery for ownCloud which includes preview for all media types supported by your ownCloud installation.

Provides a dedicated view of all images in a grid, adds image viewing capabilities to the files app and adds a gallery view to public links.

**Important** Do not enable encryption when using master (13.01.2015). It's currently broken in core.

![Screenshot](http://i.imgur.com/fxIai8t.jpg)
## Featuring
* Support for large selection of media type (depending on ownCloud setup)
* Large, zoomable previews
* Native SVG support
* Image download straight from the slideshow or the gallery
* Seamlessly jump between the gallery and the files app

Checkout the [full changelog](CHANGELOG.md) for more.

### Browser compatibility
* Desktop: Firefox, Chrome, IE 10+, Opera, Safari
* Mobile: Safari, Chrome, BlackBerry 10, Firefox, Opera

### Server requirements
* **PHP 5.4+**
* Recommended: a recent version ImageMagick

## Preparation
Here is a list of steps you might wnt to take before using the app

### Supporting more media types
First, make sure you have installed ImageMagick and its PECL extension.
Next add a few new entries to your configuration file.

```
  'preview_max_scale_factor' => 1,
  'enabledPreviewProviders' =>
  array (
    0 => 'OC\\Preview\\Image',
    1 => 'OC\\Preview\\Illustrator',
    2 => 'OC\\Preview\\Postscript',
    3 => 'OC\\Preview\\Photoshop',
    4 => 'OC\\Preview\\TIFF',
  ),
```
Look at the sample configuration in your config folder if you need more information.
That's it. you should be able to see more media types in your slideshows and galleries as soon as you've installed the app.

## Installation
Place this app in your apps folder or get the stable8 branch via the shell

```
$ git clone -b stable8 https://github.com/interfasys/galleryplus.git
```

Now you can activate it in the apps menu. It's called Gallery+

## List of patches
1. session-template-fix.patch - Fixes AppFramework sessions for public shares

