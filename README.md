# GroupEdit: Omeka S module

This module allows granting of edit and delete permissions on a site-by-site basis.

## Installation

Use the zipped releases provided on GitHub for a standard install.

You may also clone the git repository and rename the folder to `GroupEdit`.

## Usage

To make use of the new permissions set, first update a user's global role to Group Editor. Then, for any site's resources they should have access to, give them any site-based role under Sites > \[Site name\] > User permissions. This user configuration has the following permissions:

| Category           | Permission        | Group Editor             |
|--------------------|-------------------|--------------------------|
| Items & media      | Add               | Yes                      |
|                    | Edit              | Their own/their group's  |
|                    | Delete            | Their own/their group's  |
| Value annotations  | Add/Edit          | Yes                      |
| Item sets          | Add               | Yes                      |
|                    | Edit              | Their own/their group's  |
|                    | Delete            | Their own/their group's  |
| Vocabularies       | Import            | No                       |
|                    | Edit/Delete       | No                       |
| Resource templates | Add               | No                       |
|                    | Edit/Delete       | No                       |
| Private objects    | View              | Yes, regardless of group |
| Users              | Add               | No                       |
|                    | Edit              | Themself                 |
|                    | Delete            | No                       |
| Modules            | View              | Yes                      |
|                    | Install/Configure | No                       |
|                    | Use               | CSVImport only           |
| Jobs               | View              | No                       |
| Settings           | View/Change       | No                       |
| Assets             | View              | Yes                      |
|                    | Edit              | Their own                |
|                    | Delete            | Their own                |
| Sites              | Create            | No                       |
|                    | Edit/Delete       | Admin configured         |
| Site user roles    | Edit/Delete       | Admin configured         |

This is most similar to the [Author role in Omeka S core](https://omeka.org/s/docs/user-manual/admin/users/#roles-and-permissions). Permissions relating to site content (pages, navigation, etc) are handled separately by Omeka, see the [site permissions documentation](https://omeka.org/s/docs/user-manual/sites/site_users/) for more details.

## License

This module uses a GPLv3 license.