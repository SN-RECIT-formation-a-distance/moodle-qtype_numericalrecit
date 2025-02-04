$from = "moodle-qtype_numericalrecit/src/*"
$to = "shared/recitfad3/question/type/numericalrecit"
$source = "./src";

try {
    . ("..\sync\watcher.ps1")
}
catch {
    Write-Host "Error while loading sync.ps1 script." 
}