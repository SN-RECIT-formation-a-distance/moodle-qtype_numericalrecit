function Get-PluginVersion {
    $file_data = Get-Content ./src/version.php | Where-Object {$_ -like "*$plugin->release*"}

	$CharArray = $file_data.Split("=")

	$result = $CharArray[1].replace("'", "").replace(";","").trim()
	
	return $result
}

function Create-Zipfile($zipName, $pluginName, $pluginVersion){
	#remove the current zip file
	$zipFile="$($zipName)-$($pluginVersion).zip"
	
	if(Test-Path -Path $zipFile -PathType Leaf){
		Remove-Item -Path $zipFile
	}

	#zip the folder except the folders .cache and node_modules
	& "c:\Program Files\7-Zip\7z.exe" a -mx "$zipFile" "src\*"-mx0 -xr!"src\react\.cache" -xr!"src\react\node_modules" -xr!"src\react\package-lock.json"

	#set the plugin name
	& "c:\Program Files\7-Zip\7z.exe" rn "$zipFile" "src\" "$pluginName\"
}

$zipName="qtype_numericalrecit"
$pluginName="numericalrecit"
$pluginVersion = Get-PluginVersion

Create-Zipfile $zipName $pluginName $pluginVersion

#Start-Sleep -Seconds 30