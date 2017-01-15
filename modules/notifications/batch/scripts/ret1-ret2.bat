cd "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch"
"C:\xampp\php\php.exe" "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\stack_letterbox_alerts.php" -c "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\config\config.xml"
"C:\xampp\php\php.exe" "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\process_event_stack.php" -c "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\config\config.xml" -n RET1
"C:\xampp\php\php.exe" "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\process_event_stack.php" -c "C:\xampp\htdocs\maarch_entreprise\modules\notifications\batch\config\config.xml" -n RET2
