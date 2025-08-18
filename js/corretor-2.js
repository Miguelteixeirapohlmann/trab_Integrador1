// Imóveis do corretor Maria Souza (id: 2)
let imoveis = [
    { id: 2, nome: "Casa Vista Alegre", preco: 310000, imagem: "imgs/foto2.jpg", ativo: false }
];

function renderImoveisCorretor() {
    const tbody = document.querySelector("#imoveisCorretorTable tbody");
    tbody.innerHTML = "";
    imoveis.forEach(imovel => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td><img src="${imovel.imagem}" class="product-img-preview" style="max-width:80px;max-height:60px;"></td>
            <td>${imovel.nome}</td>
            <td>R$ ${imovel.preco.toLocaleString('pt-BR')}</td>
            <td><span class="badge ${imovel.ativo ? 'bg-success' : 'bg-secondary'}">${imovel.ativo ? 'À venda' : 'Desabilitado'}</span></td>
            <td>
                <button class="btn btn-sm ${imovel.ativo ? 'btn-warning' : 'btn-success'}" onclick="toggleImovel(${imovel.id})">${imovel.ativo ? 'Desabilitar' : 'Habilitar'}</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

window.toggleImovel = function(id) {
    const imovel = imoveis.find(i => i.id === id);
    if (imovel) {
        imovel.ativo = !imovel.ativo;
        renderImoveisCorretor();
    }
}

renderImoveisCorretor();
