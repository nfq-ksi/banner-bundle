NfqBannerBundle
=============================

## Installation

### Step 1: Download NfqBannerBundle using composer

Add NfqCmsPageBundle in to your composer.json:

	{
		"repositories": [
            {
              "type": "vcs",
              "url": "git@github.com:nfq-rho/banner-bundle.git"
            },
		],
    	"require": {
        	"nfq-rho/banner-bundle": "~0.3"
    	}
	}

* Add the bundle to your AppKernel.php:

```
$bundles = array(
    //...
    new \Gregwar\ImageBundle\GregwarImageBundle(), #For banner resizing
    new Nfq\BannerBundle\NfqBannerBundle(),
);
```

* Import routing

```
_nfq_banner:
    resource: @NfqBannerBundle/Resources/config/routing.yml
```

* In your templates, add:

```
{{ banner_list('frontpage_right') }}
```

* Sample configuration

```
nfq_banner:
    upload_dir: /uploads/images/banners
    locales: %locales%
    banner_places:
        frontpage_right:
            title: Frontpage right
            width: 588
            height: 414
            
#Following config, configures tinyMce editor
stfalcon_tinymce:
    include_jquery: false
    tinymce_jquery: true
    theme:
        simple:
            height: 400
            plugins:
               - "advlist autolink lists link image charmap print preview hr anchor pagebreak"
               - "searchreplace wordcount visualblocks visualchars code fullscreen"
               - "insertdatetime media nonbreaking save table contextmenu directionality"
               - "template paste textcolor noneditable -nfq_gallery"
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            toolbar2: "print preview media | forecolor backcolor"
```

`%locales%` is an array that stores your system's locales, like en_US, lt_LT, etc