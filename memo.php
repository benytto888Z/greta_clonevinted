The metadata storage is not up to date, please run the sync-metadata-storage comma
  nd to fix this issue.

  1

  -------------------------

  1

Same problem here.. I "sloved" it but dont try this at home!

I removed these lines in vendor\doctrine\migrations\lib\Doctrine\Migrations\Metadata\Storage\TableMetadataStorage.php start on line 191

$expectedTable = $this->getExpectedTable();

        if ($this->needsUpdate($expectedTable) !== null) {
            throw MetadataStorageError::notUpToDate();
        }
Then run make:migration and migrations:migrate. After success migration paste the function back.

Symfony 5.1

if you got:

Invalid platform version "maridb-10.4.13" specified. The platform version has to be specified in the format: "<major_version>.<minor_version>.<patch_version>".

just do one of

config/doctrine.yaml

doctrine:
  dbal:
    server_version: 'mariadb-10.4.13'
or in configuration file .env

DATABASE_URL=mysql://...yoursettings...?serverVersion=mariadb-10.4.13




users(id,email,roles,password)

articles(id,users_id,titre,description,slug,prix,created_at,updated_at,image,actif)

categories_articles(cat_id,art_id)

categories(id,nom,slug);