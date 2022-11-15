document.addEventListener("DOMContentLoaded",e=>{
    getWebsites()
})

const ul = document.querySelector('ul')

function getWebsites(){
    const url = "../Controller/Controller.php"
    const formData = new FormData();
    formData.append('getWebsites', true);
    
    fetch(url, { 
        method: 'POST', 
        body: formData, 
    })
    .then(response=>response.json())
    .then(json=>renderWebsites(json))
}

function renderWebsites(json){
    const template = document.querySelector("#card-template").content
    const fragment = document.createDocumentFragment()
    json.forEach((website,index) => {
        const clone = template.cloneNode(true)
        const p = clone.querySelector('p')
        const a = clone.querySelector('a')
        const img = clone.querySelector('img')
        const button = clone.querySelector('button')
        button.dataset.id = index+1
        
        p.textContent = website.name
        a.href = website.website
        a.textContent = website.website
        img.src = `img/${website.id}_${website.name.replaceAll(" ","-")}.jpg`
        button.addEventListener('click',e=>{
            takeScreenshot(button.dataset.id)
        })

        fragment.appendChild(clone)
    })
    ul.appendChild(fragment)
}
function takeScreenshot(id){
    const li = document.querySelectorAll('li')[id-1]
    const url = "../Controller/Controller.php"
    const formData = new FormData();
    formData.append('takeScreenshot', true)
    formData.append('website', li.querySelector('a').href);
    formData.append('id', li.querySelector('button').dataset.id);
    formData.append('name', li.querySelector('p').textContent);

    
    fetch(url, { 
        method: 'POST', 
        body: formData, 
    })
    .then(response=>response.json())
    .then(json=>console.log(json))
}

