# Download Cloudflare IPs and save them to variables
$cloudflareIPv4 = Invoke-WebRequest -Uri "https://www.cloudflare.com/ips-v4"
$cloudflareIPv6 = Invoke-WebRequest -Uri "https://www.cloudflare.com/ips-v6"

# Combine IPv4 and IPv6 lists and remove empty entries
$combinedIPList = ($cloudflareIPv4.Content.Split([Environment]::NewLine) + $cloudflareIPv6.Content.Split([Environment]::NewLine)) | Where-Object { -not [string]::IsNullOrEmpty($_) }

# Update IIS IP Restrictions in applicationHost.config
$configPath = Join-Path $env:SystemRoot 'System32\inetsrv\config\applicationHost.config'
[xml]$config = Get-Content $configPath
$ipSecurityNode = $config.configuration.'system.webServer'.security.ipSecurity

# Clear existing allowed entries
$ipSecurityNode.RemoveAll()

# Add new allowed entries from the list
foreach ($ip in $combinedIPList) {
    $entry = $config.CreateElement("add")
    $entry.SetAttribute("ipAddress", $ip.Split('/')[0])
    $entry.SetAttribute("subnetMask", $ip.Split('/')[1])
    $entry.SetAttribute("allowed", "true")
    $ipSecurityNode.AppendChild($entry) | Out-Null
}

# Save the updated configuration
$config.Save($configPath)

# Function to update Windows Firewall rules
function Update-WindowsFirewallRules($ipList, $ruleName) {
    # Remove existing rule
    Remove-NetFirewallRule -DisplayName $ruleName -ErrorAction SilentlyContinue

    # Add new rule for each IP in the list
    foreach ($ip in $ipList) {
        $ipRange = $ip -replace '(.*)/(.*)', '$1/$2'
        New-NetFirewallRule -DisplayName $ruleName -Direction Inbound -Action Allow -RemoteAddress $ipRange -Enabled True
    }
}

# Update Windows Firewall rules
$firewallRuleName = "Cloudflare IPs"
$allIPList = $cloudflareIPv4.Content.Split([Environment]::NewLine) + $cloudflareIPv6.Content.Split([Environment]::NewLine) | Where-Object { -not [string]::IsNullOrEmpty($_) }
Update-WindowsFirewallRules -ipList $allIPList -ruleName $firewallRuleName

Write-Host "Cloudflare IPs with their subnets imported into IIS and Windows Firewall"
