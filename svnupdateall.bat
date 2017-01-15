cd core
echo "Core" > C:\xampp\htdocs\maarch_entreprise\update.log
date /T >> C:\xampp\htdocs\maarch_entreprise\update.log
time /T >> C:\xampp\htdocs\maarch_entreprise\update.log
svn update >> C:\xampp\htdocs\maarch_entreprise\update.log
cd ..\install
svn update >> C:\xampp\htdocs\maarch_entreprise\install.log
cd ..\apps\maarch_entreprise
echo "Apps" >> C:\xampp\htdocs\maarch_entreprise\update.log
svn update >> C:\xampp\htdocs\maarch_entreprise\update.log
cd ..\..\modules
echo "Modules" >> C:\xampp\htdocs\maarch_entreprise\update.log
svn update *  >> C:\xampp\htdocs\maarch_entreprise\update.log
pause
