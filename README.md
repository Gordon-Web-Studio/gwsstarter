# gwsstarter-theme

GWSstarter Theme is a Custom WordPress development theme for Gordon Web Studio.
It's built using to main coding techs:

- [WP Rig Development Theme](https://github.com/wprig/wprig/), A progressive theme development rig for WordPress, WP Rig is built to promote the latest best practices for progressive web content and optimization.

- [Tailwindcss ](https://tailwindcss.com/), A utility-first CSS framework to build modern websites.

## Requirements

- PHP, MySQL and a webserver are required.

- [NPM with NodeJS](https://nodejs.org/) and [Composer](https://getcomposer.org/) (installed globally) are required for development.

## Getting Started

### Step 1: Set WP env

Install only the theme and plugins using a WP instance from your preferred WP dev env tool (WP Local, DevKinsta, mamp, xampp).

- Once WordPress is installed, open the OS terminal and enter/move into `wp-content` folder in the Project path.

- Clone this project repo from the installed WP `ROOT` folder into a temp folder `git clone [repo url] temp`

- Merge the files from temp to current root folder `rsync -arvzP temp/* .`

- Also merge the hidden dotfiles `rsync -arvzP temp/.[^.]* .`

- Delete the temp `rm -rf temp/`

### Step 2: Pull/Sync DB from DEV/PROD Server

- If it's initial setup without DEV/PROD sync, activate `gwsstarter-dev` theme from WP Admin panel -> Appearance -> Themes, and skip the rest of steps from this section.

- Under WP Admin Panel go to `plugins` section and `activate` the plugins: `WP Sync DB` and `WP Sync DB Media Files`.

- Pull/Sync latest DB from DEV/PROD server: Go to `Tools/Migrate DB` and, on the `Migrate` Tab, choose `Pull` option and enter the DEV Server `connection info (Site URL + Secret key)`.

- Set desire options, ex. you would like to activate the `Media Files` to import images used on DEV/PROD Server.

- If your local env don't use `https` make sure to change `find/replace` options including the corresponding HTTP protocol, Ex. https://gwsstarter.com => http://gwsstarter.wp

- Did you forget to check if your local support `https`? You will need to access the `wordpress_db` DB, look into `[wpprefix]_options` table and change the option `siteurl` and `home` to use `http`.

- Save Migration Profile for future DB synchronization

- Press `Migrate DB and Save`.

- Remember: Once Migration is finished, if you sync all DB tables including `wp_users`, remember that your local user has been overrode, WP will ask you to login again and only DB/PROD administrator users credentials will be accepted.

### Step 3: Start Coding and add new features.

Under `themes` folder there are 2 themes folders `gwsstarter-dev` that should used only for development, and `gwsstarter-theme` (compiled and compressed assets and clean version) for production.

While you're working on your `gwsstarter-dev` you will need to install all packages and dependencies from package.json.

- On OS terminal, move to `gwsstarter-dev` theme folder by running `cd themes/gwsstarter-dev/`

- Once you're in the `gwsstarter-dev` theme folder in command line, open project with your favorite code editor, if you use Visual Studio you can use `code .` from terminal.

- Place local-only theme settings in `./config/config.json`, e.g. potentially sensitive info like the BrowserSync `proxyURL` and paths to your certificate. - Again, only include the settings you want to override.

If your local environment uses a specific port number, for example `8888`, add it to the `proxyURL` setting as follows:

```
"proxyURL": "gwsstarter.wp:8081"
```

- In command line, run `npm run rig-init` to install necessary node and Composer dependencies.

```bash
$ npm run rig-init
```

### How to build WP Rig for production:

1. Follow the steps above to install WP Rig.

2. Run `npm run bundle` from inside the `gwsstarter-dev` development theme.

3. A new, production ready theme `gwsstarter-theme` will be generated in `wp-content/themes`.

4. The production theme can be activated or uploaded to a production environment.

### Available Processes

#### `dev watch` process

`npm run dev` will run the default development task that processes source files. While this process is running, source files will be watched for changes and the BrowserSync server will run. This process is optimized for speed so you can iterate quickly.

#### `dev build` process

`npm run build` processes source files one-time. It does not watch for changes nor start the BrowserSync server.

#### `translate` process

The translation process generates a `.pot` file for the theme in the `./languages/` directory.

The translation process will run automatically during production builds unless the `export:generatePotFile` configuration value in `./config/config.json` is set to `false`.

The translation process can also be run manaually with `npm run translate`. However, unless `NODE_ENV` is defined as `production` the `.pot` file will be generated against the source files, not the production files.

#### `production bundle` process

`npm run bundle` generates a production ready theme as a new theme directory and, optionally, a `.zip` archive. This builds all source files, optimizes the built files for production, does a string replacement and runs translations. Non-essential files from the `wp-rig` development theme are not copied to the production theme.

To bundle the theme without creating a zip archive, define the `export:compress` setting in `./config/config.json` to `false`:

```javascript
export: {
	compress: false
}
```

### Gulp process

WP Rig uses a [Gulp 4](https://gulpjs.com/) build process to generate and optimize the code for the theme. All development is done in the `gwsstarter-dev` development theme. Feel free to edit any `.php` files. Asset files (CSS, JavaScript and images) are processed by gulp. You should only edit the source asset files in the following locations:

-   CSS: `assets/css/src`

-   JavaScript: `assets/js/src`

-   images: `assets/images/src`

### Updating to Gulp 4

Gulp 4 uses an updated CLI (Command Line Interface). If the computer you are using already has Gulp installed, there is a good chance you have an older version of the CLI and you will encounter errors when trying to run WP Rig.

To update the Gulp CLI to work with Gulp 4, run the following commands in the command line terminal:

```
# Uninstall Gulp globally:
npm uninstall gulp -g

# Install the latest version of the Gulp 4 CLI globally:
npm install gulpjs/gulp-cli -g
```

You may have to run `npm install` again from the WP Rig directory to ensure Gulp 4 is installed and ready to run.

### Tailwindcss process

- Global tailwindcss options are located on `./config/config.json` file.

```json
{
    "dev": {
        "tailwindcss": {
            "config": "./tailwind-gwsstarter.config.js",
            "purgecss": true
        }
    }
}
```

- Tailwindcss use it's own config file `./tailwind-gwsstarter.config.js`

- If you wish to prevent PurgeCSS from removing a specific CSS selector, use `tailwind-safelist.js` file to add css classes to safelist option on PurgeCSS process.

- A global `tailwind-default.config.js` file is added just for reference.
