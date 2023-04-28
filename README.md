# PHP Pagination Library

<div>
  <img alt="Packagist PHP Version" src="https://img.shields.io/packagist/dependency-v/scrnr/pagination/php?color=orange&label=PHP&logo=php&logoColor=white">
  <img alt="Packagist Version" src="https://img.shields.io/packagist/v/scrnr/pagination?label=Packagist&logo=packagist&logoColor=white">
  <img alt="Packagist License" src="https://img.shields.io/packagist/l/scrnr/pagination?label=LICENSE&logo=reacthookform&logoColor=white">
</div>

## Table Of Contents

* [Description](#description)
* [Installation](#installation)
* [Usage](#usage)
* [Customization Pagination Settings](#customization)
* [Conclusion](#conclusion)
* [Author](#author)
* [License](#license)

<a name='description'></a>
## Description [:top:](#table-of-contents)

This is a simple and flexible PHP library for creating pagination in your web applications.  It allows you to easily paginate large sets of data and display them in smaller, more manageable chunks. 

<a name='installation'></a>
## Installation [:top:](#table-of-contents)

You can install the library using [Composer](https://getcomposer.org/). Simply add the following lines to your `composer.json` file and run `composer install`:

```json
"require": {
  "scrnr/pagination": "*"
}
```

Or you can use this **command**:

```bash
composer require scrnr/pagination
```

<a name='usage'></a>
## Usage [:top:](#table-of-contents)

To use the pagination library in your application, you need to include the [Composer](https://getcomposer.org/) autoload file and create a new instance. After creating an instance, you can call the `getPagination()` method, passing an ***optional*** array of options as a parameter. This method to generate a string representation of the pagination links.

### Here is an example of usage

```php
<?php

// Include the namespace
use Scrnr\Pagination\Pagination;

// Require the Composer autoload file
require_once __DIR__ . '/vendor/autoload.php';

// Create a new Pagination instance
$pagination = new Pagination();

// Pagination settings
$options = [
  'isGetPagination' => true,
  'currentPage' => (int) ($_GET['page'] ?? 1),
  'limitItems' => 50,
  'limitPages' => 15,
  'totalItems' => 500,
];

// Get the pagination links
$links = $pagination->getPagination($options);

// Display the pagination links
echo $links;
```
### Output

```html
<nav class='pagination' id='pagination'>
  <ul class='pagination__ul'>
    <li class='pagination__item'>
      <a href='/?page=1' class='pagination__link active'>1</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=2' class='pagination__link next'>2</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=3' class='pagination__link'>3</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=4' class='pagination__link'>4</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=5' class='pagination__link'>5</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=6' class='pagination__link'>6</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=7' class='pagination__link'>7</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=8' class='pagination__link'>8</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=9' class='pagination__link'>9</a>
    </li>
    <li class='pagination__item'>
      <a href='/?page=10' class='pagination__link'>10</a>
    </li>
    </ul>
</nav>
```

In this example, we first include the namespace for the Pagination class. We then require the [Composer](https://getcomposer.org/) autoload file, which loads the necessary classes and files for our project. Finally, we create a new instance of the Pagination class and use it to generate pagination.

<a name='customization'></a>
## Customization Pagination Settings [:top:](#table-of-contents)

You can customize the pagination settings by passing an array of options to the `getPagination()` method. The following options are available:


| Option Name | Type | Default | Description |
| :------: | :----: | :-------: | :----------- |
| `url` | ***string*** | `''` | Represents *URL* of the page being paginated. This setting is **required** to ensure that the correct page is displayed. |
| `uri` | ***string*** | `'/'` | Represents *URI* of the page being paginated. This setting is **required** to ensure that the correct page is displayed. |
| `showArrowLinks`\* | ***boolean*** | `true` | Determines whether *arrows* are displayed at the edges of the pagination. These arrows can be used to navigate to the *first* or *last* page. |
| `showDummyLinks`\* | ***boolean*** | `(showArrowLinks === true) ? true : false`| Determines whether *dummy links* are displayed. When there are many pagination pages and the `showArrowLinks` option is set to `true`, the *dummy links* will be displayed before the *arrows links*. |
| `isGetPagination` | ***boolean*** | `false` | Determines whether pagination information is included in the *URL* as *GET parameters*. When this setting is **enabled**, the pagination information is included in the URL as query parameters (e.g., `?page=2` or `?p=2`). When this setting is **disabled**, pagination information will be included in the *URL as *friendly URLs* (e.g., `/page/2` or `/p/2`). |
| `currentPage` | ***integer*** | `1` | Represents the *current* page number. This setting is **required** to ensure that the correct page is highlighted. |
| `limitItems` | ***integer*** | `15` | Represents the *number of items* displayed on each page. This setting is **required** to ensure that the correct number of items are displayed. |
| `limitPages` | ***integer*** | `7` | Represents the *maximum number of pagination links* to display. If there are more pages than this setting, the library will automatically limit the number of links displayed. |
| `totalItems` | ***integer*** | `65` | Represents the *total number of items* being paginated. This setting is **required** to calculate the total number of pages. |
| `id` | ***string*** | `'pagination'` | The *ID* of the pagination element. |
| `navClass` | ***string*** | `'pagination'` | Represents the CSS class for the `<nav>` tag within tha pagination. |
| `ulClass` | ***string*** | `'pagination__ul'` | Represents the CSS class for the `<ul>` tag within the pagination. |
| `liClass` | ***string*** | `'pagination__item'` | Represents the CSS class for each `<li>` tag within the pagination. |
| `linkClass` | ***string*** | `'pagination__link'` | Represents the CSS class for each `<a>` tag within the pagination. |
| `activeClass` | ***string*** | `'pagination__link active'` | Represents the CSS class for the *active page* link |
| `nextClass` | ***string*** | `'pagination__link next'` | Represents the CSS class for the *next page* link. |
| `prevClass` | ***string*** | `'pagination__link previous'` | Represents the CSS class for the *previous page* link. |
| `arrowLinkClass` | ***string*** | `'pagination__link arrow'` | Represents the CSS class for the *arrow elements* within the pagination. |
| `dummyLinkClass` | ***string*** | `'pagination__link dummy'` | Represents the CSS class for the *dummy link elements* within the pagination. |

### NOTES

| Option Name | NOTE |
| :---------: | :--- |
|`showArrowLinks`| To display the *first and last arrows*, the value of the `limitPages` option must be **greater than or equal** to `4`. |
|`showDummyLinks`| To display *dummy links*, the value of the `limitPages` option must be **greater than or equal** to `6` and `showArrowLinks` must be `true`. |

<a name='conclusion'></a>
## Conclusion [:top:](#table-of-contents)

The Pagination library is a simple and flexible solution for paginating large datasets. It is easy to use, easy to customize and compatible with any PHP project.

<a name='author'></a>
## Author [:top:](#table-of-contents)

👤 GitHub: [scrnr](https://github.com/scrnr)

<a name='license'></a>
## License [:top:](#table-of-contents)

This library is released under the MIT License. Please review the [LICENSE](https://github.com/scrnr/pagination-library-php/blob/main/LICENSE) file  for more information.
