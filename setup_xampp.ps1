$ErrorActionPreference = 'Stop'

$httpdConf  = 'C:\xampp\apache\conf\httpd.conf'
$vhostsConf = 'C:\xampp\apache\conf\extra\httpd-vhosts.conf'
$hostsFile  = 'C:\Windows\System32\drivers\etc\hosts'

function Backup-File([string]$path) {
  if (!(Test-Path $path)) { throw "Missing file: $path" }
  $bak = "$path.bak.$(Get-Date -Format 'yyyyMMddHHmmss')"
  Copy-Item -Force $path $bak
}

Backup-File $httpdConf
Backup-File $vhostsConf
try {
  Backup-File $hostsFile
} catch {
  Write-Output "WARNING: Could not backup hosts file (needs Administrator). Hosts changes will be attempted but may fail."
}

# Enable mod_rewrite + vhosts include
$httpd = Get-Content $httpdConf -Raw
$httpd = $httpd -replace '(?m)^#\s*(LoadModule\s+rewrite_module\s+modules/mod_rewrite\.so\s*)$','$1'
$httpd = $httpd -replace '(?m)^#\s*(Include\s+conf/extra/httpd-vhosts\.conf\s*)$','$1'
Set-Content -Path $httpdConf -Encoding ASCII -Value $httpd

# Add vhost for ulimi3.local if not present
$vhosts = Get-Content $vhostsConf -Raw
if ($vhosts -notmatch '(?im)ServerName\s+ulimi3\.local') {
  $block = @'

<VirtualHost *:80>
    ServerName ulimi3.local
    DocumentRoot "C:/xampp/htdocs/ulimi3/public"

    <Directory "C:/xampp/htdocs/ulimi3/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
'@
  Add-Content -Path $vhostsConf -Encoding ASCII -Value $block
}

# Add hosts entry if missing (may require admin)
try {
  $hosts = Get-Content $hostsFile -Raw
  if ($hosts -notmatch '(?m)^\s*127\.0\.0\.1\s+ulimi3\.local\s*$') {
    Add-Content -Path $hostsFile -Encoding ASCII -Value "`r`n127.0.0.1 ulimi3.local`r`n"
  }
} catch {
  Write-Output "WARNING: Could not update hosts file. Run PowerShell as Administrator and re-run this script, or add: 127.0.0.1 ulimi3.local manually."
}

Write-Output 'OK: Apache config updated. Restart Apache from XAMPP Control Panel.'
