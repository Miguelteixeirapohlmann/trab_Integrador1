/*!
* Start Bootstrap - Creative v7.0.7 (https://startbootstrap.com/theme/creative)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE)
*/
//
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink')
        } else {
            navbarCollapsible.classList.add('navbar-shrink')
        }

    };

    // Shrink the navbar 

    // Corrige erro: responsiveNavItems não definido
    var responsiveNavItems = document.querySelectorAll('#navbarResponsive .nav-link');
    var navbarToggler = document.body.querySelector('.navbar-toggler');
    if (responsiveNavItems && navbarToggler) {
        responsiveNavItems.forEach(function (responsiveNavItem) {
            responsiveNavItem.addEventListener('click', () => {
                if (window.getComputedStyle(navbarToggler).display !== 'none') {
                    navbarToggler.click();
                }
            });
        });
    }

    // Activate SimpleLightbox plugin for portfolio items
    if (typeof SimpleLightbox !== 'undefined') {
        new SimpleLightbox({
            elements: '#portfolio a.portfolio-box'
        });
    }

});


    image: ["imgs/foto7.jpg"]
function renderProducts() {
    const tbody = document.querySelector("#productsTable tbody");
    if (!tbody) return;
    tbody.innerHTML = "";
    products.forEach((prod, idx) => {
        const carouselId = `carousel-${idx}`;
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td style="min-width:120px;">
                <div id="${carouselId}" class="carousel slide" data-bs-ride="carousel" style="width:100px">
                    <div class="carousel-inner">
                        ${prod.image.map((img, i) => `
                            <div class="carousel-item${i === 0 ? ' active' : ''}">
                                <img src="${img}" class="d-block w-100" alt="${prod.name}" style="height:60px;object-fit:cover;">
                            </div>
                        `).join('')}
                    </div>
                    ${prod.image.length > 1 ? `
                    <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" style="width:16px;height:16px" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                        <span class="carousel-control-next-icon" style="width:16px;height:16px" aria-hidden="true"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                    ` : ''}
                </div>
            </td>
            <td><a href="${prod.link}" target="_blank">${prod.name}</a></td>
            <td>${prod.type.charAt(0).toUpperCase() + prod.type.slice(1)}</td>
            <td>R$ ${prod.price.toFixed(2)}</td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="editProduct(${idx})"><i class="fas fa-edit"></i> Editar</button>
                <button class="btn btn-danger btn-sm" onclick="removeProduct(${idx})"><i class="fas fa-trash"></i> Remover</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function removeProduct(idx) {
    if (confirm("Tem certeza que deseja remover este produto?")) {
        products.splice(idx, 1);
        renderProducts();
    }
}


// Adicionar produto
var addProductForm = document.getElementById("addProductForm");
if (addProductForm) {
    addProductForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const name = document.getElementById("productName").value;
        const type = document.getElementById("productType").value;
        const price = parseFloat(document.getElementById("productPrice").value);
        const imageInput = document.getElementById("productImage");
        let images = [];
        if (imageInput.files && imageInput.files.length > 0) {
            for (let i = 0; i < imageInput.files.length; i++) {
                images.push(URL.createObjectURL(imageInput.files[i]));
            }
        } else if (typeof editingIndex !== 'undefined' && editingIndex !== null) {
            images = products[editingIndex].image;
        } else {
            images = ["img/placeholder.png"];
        }

        if (typeof editingIndex !== 'undefined' && editingIndex !== null) {
            products[editingIndex] = { name, type, price, image: images };
            editingIndex = null;
            document.querySelector('#addProductForm button[type="submit"]').innerHTML = '<i class="fas fa-plus"></i> Adicionar';
        } else {
            products.push({ name, type, price, image: images });
        }
        renderProducts();
        this.reset();
    });
}

// Editar produto
function editProduct(idx) {
    const prod = products[idx];
    document.getElementById("productName").value = prod.name;
    document.getElementById("productType").value = prod.type;
    document.getElementById("productPrice").value = prod.price;
    // Não é possível restaurar a imagem no input file por segurança
    editingIndex = idx;
    // Muda o texto do botão para "Salvar"
    document.querySelector('#addProductForm button[type="submit"]').innerHTML = '<i class="fas fa-save"></i> Salvar';
}

renderProducts();
