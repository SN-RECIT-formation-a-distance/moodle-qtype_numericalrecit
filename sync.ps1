$from = "moodle-qtype_numericalrecit/src/*"
$to = "shared/recitfad/question/type/numericalrecit"

try {
    . ("..\sync\watcher.ps1")
}
catch {
    Write-Host "Error while loading sync.ps1 script." 
}