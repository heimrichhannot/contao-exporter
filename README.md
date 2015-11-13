# Exporter

A backend module for export configurations.

## Entities
- tl_exporter


## How to use

STEP I
- Go to your dca file
- Define your global operation as usually and set 'href' to 'key=export'
'''
'global_operations' => array
(
	'export' => array
	(
		'href' => 'key=export'
	),
	'export_xls' => array
	(
		'href' => 'key=export_xls'
	),
),
'''

STEP II
- Go to your config.php
- Add your backend module as usually and set the 'export'-function for the global operations
'''
'my_be_module' => array
(
	'export' => array('\HeimrichHannot\Exporter\Exporter', 'export'),
	'export_xls' => array('\HeimrichHannot\Exporter\Exporter', 'export')
),
'''

STEP III
- Create a configuration in the backend for the global operation and table