
// Imóveis do corretor João Silva (id: 1) - Atualizados conforme o sistema

// Dados das casas 1, 2 e 3
const casas = [
        {
                nome: "Casa em Santo Antônio da Patrulha",
                preco: 300000,
                tipo: "Residencial",
                imagens: [
                        "imgs/Casa1/Casa1.0.jpg",
                        "imgs/Casa1/Casa1.1.jpg",
                        "imgs/Casa1/Casa1.2.jpg",
                        "imgs/Casa1/Casa1.3.jpg",
                        "imgs/Casa1/Casa1.4.jpg",
                        "imgs/Casa1/Casa1.5.jpg",
                        "imgs/Casa1/Casa1.6.jpg",
                        "imgs/Casa1/Casa1.7.jpg",
                        "imgs/Casa1/Casa1.8.jpg",
                        "imgs/Casa1/Casa1.9.jpg"
                ]
        },
        {
                nome: "Casa em Taquara",
                preco: 220000,
                tipo: "Residencial",
                imagens: [
                        "imgs/Casa2/Casa2.0.jpg",
                        "imgs/Casa2/Casa2.1.jpg",
                        "imgs/Casa2/Casa2.2.jpg",
                        "imgs/Casa2/Casa2.3.jpg",
                        "imgs/Casa2/Casa2.4.jpg",
                        "imgs/Casa2/Casa2.5.jpg",
                        "imgs/Casa2/Casa2.6.jpg",
                        "imgs/Casa2/Casa2.7.jpg",
                        "imgs/Casa2/Casa2.8.jpg",
                        "imgs/Casa2/Casa2.9.jpg",
                        "imgs/Casa2/Casa2.10.jpg",
                        "imgs/Casa2/Casa2.11.jpg",
                        "imgs/Casa2/Casa2.12.jpg",
                        "imgs/Casa2/Casa2.13.jpg"
                ]
        },
        {
                nome: "Casa em Taquara Alto Padrão",
                preco: 3000000,
                tipo: "Alto Padrão",
                imagens: [
                        "imgs/Casa3/Casa3.0.jpg",
                        "imgs/Casa3/Casa3.1.jpg",
                        "imgs/Casa3/Casa3.2.jpg",
                        "imgs/Casa3/Casa3.3.jpg",
                        "imgs/Casa3/Casa3.4.jpg",
                        "imgs/Casa3/Casa3.5.jpg",
                        "imgs/Casa3/Casa3.6.jpg",
                        "imgs/Casa3/Casa3.7.jpg",
                        "imgs/Casa3/Casa3.8.jpg",
                        "imgs/Casa3/Casa3.9.jpg"
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
                        <div class="card mb-4" style="max-width: 400px; margin: 0 auto;">
                                <div id="${carouselId}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                                ${casa.imagens.map((img, i) => `
                                                        <div class="carousel-item${i === 0 ? ' active' : ''}">
                                                                <img src="${img}" class="d-block w-100" alt="${casa.nome}" style="height:200px;object-fit:cover;">
                                                        </div>
                                                `).join('')}
                                        </div>
                                        ${casa.imagens.length > 1 ? `
                                        <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Próximo</span>
                                        </button>
                                        ` : ''}
                                </div>
                                <div class="card-body">
                                        <h5 class="card-title">${casa.nome}</h5>
                                        <p class="card-text mb-1"><strong>Valor:</strong> R$ ${casa.preco.toLocaleString('pt-BR')}</p>
                                        <p class="card-text"><strong>Tipo:</strong> ${casa.tipo}</p>
                                </div>
                        </div>
                `;
                container.innerHTML += carousel;
        });
}

// Chame esta função após o DOM carregar
document.addEventListener("DOMContentLoaded", renderCarrosseisCasas);
