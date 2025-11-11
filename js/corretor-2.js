
// Imóveis do corretor Maria Oliveira (id: 2)
// Dados das casas 4, 5 e 6
const casas = [
    {
        nome: "Casa em Parobé",
        preco: 180000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa4/Casa4.0.jpg",
            "imgs/Casa4/Casa4.2.jpg",
            "imgs/Casa4/Casa4.3.jpg",
            "imgs/Casa4/Casa4.4.jpg",
            "imgs/Casa4/Casa4.5.jpg",
            "imgs/Casa4/Casa4.6.jpg",
            "imgs/Casa4/Casa4.7.jpg",
            "imgs/Casa4/Casa4.8.jpg",
            "imgs/Casa4/Casa4.9.jpg"
        ]
    },
    {
        nome: "Casa em Igrejinha",
        preco: 250000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa5/Casa5.0.jpg",
            "imgs/Casa5/Casa5.1.jpg",
            "imgs/Casa5/Casa5.2.jpg",
            "imgs/Casa5/Casa5.3.jpg",
            "imgs/Casa5/Casa5.4.jpg",
            "imgs/Casa5/Casa5.5.jpg",
            "imgs/Casa5/Casa5.6.jpg",
            "imgs/Casa5/Casa5.7.jpg",
            "imgs/Casa5/Casa5.8.jpg",
            "imgs/Casa5/Casa5.9.jpg",
            "imgs/Casa5/Casa5.10.jpg"
        ]
    },
    {
        nome: "Casa em Rolante",
        preco: 320000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa6/Casa6.0.jpg",
            "imgs/Casa6/Casa6.1.jpg",
            "imgs/Casa6/Casa6.2.jpg",
            "imgs/Casa6/Casa6.3.jpg",
            "imgs/Casa6/Casa6.4.jpg",
            "imgs/Casa6/Casa6.5.jpg",
            "imgs/Casa6/Casa6.6.jpg",
            "imgs/Casa6/Casa6.7.jpg",
            "imgs/Casa6/Casa6.8.jpg",
            "imgs/Casa6/Casa6.9.jpg"
        ]
    }
];

function renderCarrosseisCasas() {
    const container = document.getElementById("casasCarrosseis");
    if (!container) return;
    container.innerHTML = "";
    casas.forEach((casa, idx) => {
        const carouselId = `carousel-casa-${idx}`;
        const carousel = `
            <div class=\"card mb-4\" style=\"max-width: 400px; margin: 0 auto;\">
                <div id=\"${carouselId}\" class=\"carousel slide\" data-bs-ride=\"carousel\">
                    <div class=\"carousel-inner\">
                        ${casa.imagens.map((img, i) => `
                            <div class=\"carousel-item${i === 0 ? ' active' : ''}\">
                                <img src=\"${img}\" class=\"d-block w-100\" alt=\"${casa.nome}\" style=\"height:200px;object-fit:cover;\">
                            </div>
                        `).join('')}
                    </div>
                    ${casa.imagens.length > 1 ? `
                    <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#${carouselId}\" data-bs-slide=\"prev\">
                        <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
                        <span class=\"visually-hidden\">Anterior</span>
                    </button>
                    <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#${carouselId}\" data-bs-slide=\"next\">
                        <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
                        <span class=\"visually-hidden\">Próximo</span>
                    </button>
                    ` : ''}
                </div>
                <div class=\"card-body\">
                    <h5 class=\"card-title\">${casa.nome}</h5>
                    <p class=\"card-text mb-1\"><strong>Valor:</strong> R$ ${casa.preco.toLocaleString('pt-BR')}</p>
                    <p class=\"card-text\"><strong>Tipo:</strong> ${casa.tipo}</p>
                </div>
            </div>
        `;
        container.innerHTML += carousel;
    });
}

document.addEventListener("DOMContentLoaded", renderCarrosseisCasas);
