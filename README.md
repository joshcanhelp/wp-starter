This is a WordPress starter theme. There are other WordPress starter themes but this one is mine. 

I call this "Allons-y," partly because I want you to know that I know a little bit of French and that makes me a little bit fancy but also because I find myself mumbling "let's go ..." when I'm doing something repetitive and want to get it done quickly (like, say, cobbling together the disparate pieces of a starter theme). "Let's go" in French is, you guessed it, "Allons-Y."

The instructions below walk you through how to adjust this theme to fit your project and remove parts that may not be helpful. They were written for my projects so, in some cases (like changes to style.css), you'll be making a less/more/different changes. I believe that how I develop is the most efficient, precise, and effective way so if your way is different, you should probably change it to be more like my way. Fact. 

---
## Let's Go

The commands below assume you: 

- have [npm](https://docs.npmjs.com/getting-started/installing-node) installed globally 
- have [Hub](https://github.com/github/hub) installed and aliased to the `git` command)
- have a working install of the latest WordPress version. 
- have a functioning sense of humor

**Note:** All file names and directories are relative from the theme, unless indicated otherwise.

### Option 1: The Hard Way - Namespace Theme

Use this if you don't want any of my namespacing or are going to make significant changes to how this theme works. Or if you intend to use this theme as a parent theme. Or if you're a glutten for punishment. 

1. Pick a slug for this theme based on the project name. It should only contain lowercase letters, numbers, and dashes. This will be used throughout the theme and in this documentation. 
2. Go to */WP_ROOT/wp-content/themes/* and create a directory for this theme using the slug picked above. 
3. Clone this repo into that directory: `git clone joshcanhelp/wp-starter theme-slug`
4. Make sure that WP_DEBUG is turned on in /wp-config.php
5. Log into wp-admin, go to **wp-admin > Appearance > Themes** and activate the starter theme
6. Make sure there are no errors appearing on the front or back end of the site
7. Delete the *.git/* directory
8. Edit *screenshot.psd* to add the site logo and remove "Starter Theme;" when complete, save for web as an 8-bit *screenshot.png*
9. If the logo is final, delete *screenshot.psd*. Otherwise, leave it there until the screenshot is complete
10. Open *style.css* and make the following changes:
	1. Change `Theme Name:` to the customer or project name; keep it short and hilarious
	2. Change `Theme URI:` to a link that directs folks using the theme somewhere extremely helpful like your personal homepage or Instagram account
	3. Change `Author:` to your name, your company's name, or your cat's name
	4. Change `Author URI:` to your Wikipedia entry, Reddit profile, or Google Map link to your house
	5. Change `Description:` to something more hilarious than you did for the Theme Name
	6. Change `Version:` to 0.1.0 to show that this is modified from the original somehow
	7. Leave both `License:` and `License URI:` alone for the love of god
	8. Change `Text Domain:` to the project slug chosen above
11. Refresh the **wp-admin > Appearance > Themes** page to make sure the changes show
12. Open *package.json* and make the following changes:
	1. Change "name" to match the theme slug
	2. Change "version" to "0.1.0"
	3. Change "description" to match the Description used in 10.5 above
13. In your CLI (still in the theme directory), install all deps: `npm install`. This should finish without errors. If it does not finish without errors, you're on your own and I'm sorry but it will be good for you and turn out well, I promise
14. Again in the CLI, test Grunt with the following commands (all should finish successfully) 
	1. `grunt sass`
	2. `grunt browserify`
	3. `grunt inlinecss`
15. Open *functions.php* and make the following changes:
	1. Find the first instance of the `ALLONSY_THEME_VERSION` constant and [PhpStorm] Right Click > Refactor > Rename; for example `ALLONSY_THEME_VERSION` would change to `THEME_SLUG_THEME_VERSION`
	2. Refactor `ALLONSY_THEME_VERSION_OPT_NAME` similarly and change the value to match your theme slug
	3. Change the option name in `allonsy_plugin_activation` to match the theme slug
	4. Review `allonsy_hook_after_setup_theme` function for things to remove or change
	5. Review `allonsy_hook_init` function for things to remove or change
	6. Forgive me for not being very funny in this bullet point block
16. Refresh wp-admin and the homepage of the site to make sure there are no errors or notices
17. [PhpStorm] Create a new local scope:
	1. Go to **Preferences > Appearance > Scopes**
	2. Click the "+" to add a new Local scope
	3. For "Name" use the theme slug
	4. Find the theme folder, click to highlight, and click **Include Recursively**
	5. Click */node_modules* to highlight, and click **Exclude Recursively**
	6. Select all the files (not directories) in */assets/css* and click **Exclude**
	7. Select all the files (not directories) in */assets/js* and click **Exclude**
	8. Click */assets/fonts* and click **Exclude Recursively**
	9. Click */assets/js/src/libs* and click **Exclude Recursively**
	10. Click */assets/css/sass/libs* and click **Exclude Recursively**
	11. Click */emails* and click **Exclude Recursively**
	12. Click */includes/classes/PhpFormBuilder.php* and click **Exclude**
18. [PhpStorm] Now run a Code Inspection on this project so far at **Code > Inspect Code**; make sure to select the custom scope created above to only include the new files

### Option 2: The Easy Way - Child Theme

If you're just building out a theme for a site, be it client, family, friend, personal, or otherwise, your best bet is to create a child theme off of this one. Quick steps:

1. Follow steps 1 - 7 from the section above using the slug "allons-y" to create the parent theme. I'll wait. 
2. Follow step 1 with a new slug for this specific site; create a directory with that slug
3. Follow step 8 above for this new theme directory but use a cat picture
4. Follow step 10 above by also add a line with `Template: allons-y`
5. Go to **wp-admin > Appearance > Themes** page and make sure the child theme is there; if there was an issue, you should see your theme in the "Broken Themes" section at the bottom
6. Activate the theme and you're ready to go. The [Theme Handbook resource on child themes](https://developer.wordpress.org/themes/advanced-topics/child-themes/) is an excellent place to learn all about the magic that you just instantiated

Whichever way you chose, go make a cup of oolong tea or order a new shirt or something because you're now fancy, like me.

---


## Now What?

With your theme, tea, and shirt ready to go, you're now free to build excellent web with WordPress.

Each file has a block at the top to explain what's located in each file and how to use it. I'm not going to be there to watch over your shoulder so I trust you'll do the right thing. Here are a few general guidelines:

I have a number of things that I'm planning on adding here, including:

- Make sure `/admin/wysiwyg-editor-functions.php` is working and not too specific for any one site. The functions and features here are, I've found, quite finicky so it's nice to have an example. 
- Create a few page templates to show off what can be done with Bourbon/Neat. I don't want to go too far down the "shove markup down your throat" road since that's really effing annoying with themes you take over (ask me how I know). 
- Add some basic styling for some of the block partials that are there and, while I'm at it, add more of those block partials
- Add a page template for the sitemap, maybe, if you're good
- Add options for custom admin HTML emails
- Fix all of your issues, requests, and customizations forever



