function enablePasswordFields(aFields = []) {
    aFields.forEach(function(sFieldName){
        let oElement = document.getElementsByName(sFieldName)[0]
        oElement.disabled = !oElement.disabled
    })
}