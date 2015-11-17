# Exporter

A backend module for exporting any contao entity.

## How to use

### Step 1
Define your global operation in your entity's dca as follows:

```
'global_operations' => array
(
    'export' => \HeimrichHannot\Exporter\Exporter::getGlobalOperation('export',
                $GLOBALS['TL_LANG']['tl_my_entity']['export'],
                'system/modules/mymodule/assets/img/icon_export.png')
),
```

### Step 2
Add your backend module in your entity's config.php as follows:

```
$GLOBALS['BE_MOD']['mygroup'] = array
(
    'export' => \HeimrichHannot\Exporter\Exporter::getBackendModule(),
    'export_xls' => \HeimrichHannot\Exporter\Exporter::getBackendModule()
),
```

### Step 3
Create a configuration for your export by using the exporter's backend module (group devtools).