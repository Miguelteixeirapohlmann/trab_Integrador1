
// Imóveis do corretor Pedro Souza (id: 3)
// Dados das casas 7, 8 e 9
const casas = [
    {
        nome: "Casa em Sapiranga",
        preco: 210000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa7/Casa7.0.jpg",
            "imgs/Casa7/Casa7.1.jpg",
            "imgs/Casa7/Casa7.2.jpg",
            "imgs/Casa7/Casa7.3.jpg",
            "imgs/Casa7/Casa7.4.jpg",
            "imgs/Casa7/Casa7.5.jpg",
            "imgs/Casa7/Casa7.6.jpg",
            "imgs/Casa7/Casa7.7.jpg",
            "imgs/Casa7/Casa7.8.jpg",
            "imgs/Casa7/Casa7.9.jpg"
        ]
    },
    {
        nome: "Casa em Nova Hartz",
        preco: 195000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa8/Casa8.0.jpg",
            "imgs/Casa8/Casa8.1.jpg",
            "imgs/Casa8/Casa8.2.jpg",
            "imgs/Casa8/Casa8.3.jpg",
            "imgs/Casa8/Casa8.4.jpg",
            "imgs/Casa8/Casa8.5.jpg",
            "imgs/Casa8/Casa8.6.jpg",
            "imgs/Casa8/Casa8.7.jpg",
            "imgs/Casa8/Casa8.8.jpg",
            "imgs/Casa8/Casa8.9.jpg"
        ]
    },
    {
        nome: "Casa em Campo Bom",
        preco: 275000,
        tipo: "Residencial",
        imagens: [
            "imgs/Casa9/Casa9.0.jpg",
            "imgs/Casa9/Casa9.1.jpg",
            "imgs/Casa9/Casa9.2.jpg",
            "imgs/Casa9/Casa9.3.jpg",
            "imgs/Casa9/Casa9.4.jpg",
            "imgs/Casa9/Casa9.5.jpg",
            "imgs/Casa9/Casa9.6.jpg",
            "imgs/Casa9/Casa9.7.jpg",
            "imgs/Casa9/Casa9.8.jpg",
            "imgs/Casa9/Casa9.9.jpg"
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
