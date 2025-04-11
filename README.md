# The Analytics block #

The block_analytics plugin is a custom block that allows users to embed Metabase dashboards within Custom Pages. It is designed to enable the integration of analyses while ensuring data security and appropriate user permissions.


## Features ##

- Embeds a Metabase dashboard within a Moodle Custom Page.
- Requires specific permissions to add and view the block.
- Ensures security through Moodle-side and Metabase-side authorization.
- Allows configuration of dashboard settings via the block interface.
- Supports URL parameters for customizing the embedded dashboard.

## Requirements ##

This block relies on an external analytics service, such as https://www.metabase.com/, to display embedded dashboards via iframe.

To use this plugin, you will need:
- An active Metabase instance
- A configured dashboard ID to embed.
- A signed JWT token for secure embedding (see: https://www.metabase.com/docs/latest/embedding/start).

You can set the required configuration values (URL and secret key) in the plugin settings within Moodle:
- **metabaseurl** – base URL of your Metabase instance (e.g. `https://metabase.com`)
- **metabasekey** – secret key used to sign the JWT token

## Dependencies ##

This plugin requires the local_custompage plugin to function correctly. The Custom Page functionality is not available in standard Moodle. Ensure that local_custompage is installed and configured before using block_analytics.


## Installation ##

1. Download the plugin package.
2. Place the block_analytics folder in your Moodle installation under blocks/.
3. Navigate to Site Administration > Plugins > Install plugins to complete the installation.
4. Ensure that the local_custompage plugin is installed and enabled.
5. Configure the site-wide Metabase settings in Site Administration > Plugins > Blocks > Analytics Block Settings.


## Configuration ##

After installation, the plugin requires some configuration:
1. Metabase Site URL: The base URL of your Metabase instance.
2. Metabase Secret Key: The secret key used to generate JWT tokens for embedding dashboards.


## Permissions ##

This block includes two main permissions that control access:
1. block/analytics:addinstance - Allows adding the block to a Custom Page (default: Manager role).
2. block/analytics:view - Allows viewing the block's embedded content (default: Manager role).


## Security Considerations ##

### Moodle-side Authorization ###
- The block can only be added to Custom Pages, not courses or dashboards.
- Users must have the appropriate permissions to add or view the block.
- The embedded dashboard requires at least one Audience restriction on the Custom Page.

### Metabase-side Authorization ###
- The plugin uses JWT authentication to securely embed dashboards.
- The embedded URL is valid for 5 to 10 minutes before expiration.
- The Metabase instance should be configured to expose only the /embed/dashboard/* URLs.


## Usage Example: ##
To embed a Metabase dashboard with ID 50, use the following settings:
- Dashboard ID: 50
- Extra URL Parameters: bordered=true&titled=true

A sample JWT generation code snippet:

    var jwt = require("jsonwebtoken");
    var METABASE_SITE_URL = "https://metabase.example.com";
    var METABASE_SECRET_KEY = "your_secret_key";
    var payload = {
    resource: { dashboard: 50 },
    params: {},
    exp: Math.round(Date.now() / 1000) + (10 * 60) // 10-minute expiration
    };
    var token = jwt.sign(payload, METABASE_SECRET_KEY);
    var iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=true&titled=true";