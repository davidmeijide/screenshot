document.addEventListener("DOMContentLoaded",e=>{
    getWebsites()
})

const ul = document.querySelector('ul')
const login = document.querySelector("#login")
login.addEventListener('click',e=>{
    e.preventDefault()
    window.open("http://localhost:8000/screenshot_generator/src/Login.php","Google Login","width=400,height=600")
    document.querySelectorAll('li').forEach(li=>{
        const button = li.querySelector('button')
        button.style.cursor = "default"
        button.disabled = false

    })
})

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
        //const button_link = clone.querySelector('.buttonLink')
        const button = clone.querySelector('button')
        button.dataset.id = index+1
        button.style.cursor = "not-allowed"
        //button_link.href = `http://localhost:8000/screenshot_generator/Controller/Controller.php?id=${index+1}`
        p.textContent = website.name
        a.href = website.website
        a.textContent = website.website
        img.src = `img/${website.id}_${website.name.replaceAll(" ","-")}.jpg`
        button.addEventListener('click',e=>{
            e.preventDefault()
            takeScreenshot(index+1)
        })
        fragment.appendChild(clone)
    })
    ul.appendChild(fragment)
}
function takeScreenshot(id){
    const li = document.querySelectorAll('li')[id-1]
    const url = "../Controller/Controller.php"
    const formData = new FormData();
    formData.append('id', id);
    formData.append('website', li.querySelector('a').href);
    formData.append('name', li.querySelector('p').textContent);

    //loading animation
    loadingAnimation(id)
    
    fetch(url, { 
        method: 'POST', 
        body: formData, 
    })
    .then(response=>response.json())
    .then(json=>{
        console.log(json)

        if(typeof json.id !== 'undefined'){
            loadingSuccess(id)
        }
    })
}

function loadingAnimation(buttonId){
    const li = document.querySelectorAll('li')[buttonId-1]
    const button = li.querySelector('button')
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>Loading'
    button.classList.add('buttonload')
}

function loadingSuccess(buttonId){
    const li = document.querySelectorAll('li')[buttonId-1]
    const button = li.querySelector('button')
    button.innerHTML = 'Saved!'
    button.classList.remove('buttonload')
    const img = li.querySelector('img')
    img.src = img.src
}

function enableButtons(){

}
