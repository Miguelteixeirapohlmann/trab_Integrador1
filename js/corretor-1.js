// Imóveis do corretor João Silva (id: 1)
let imoveis = [
    { id: 1, nome: "Casa Realengo", preco: 250000, imagem: "imgs/Foto9.jpg", ativo: true },
    { id: 3, nome: "Casa Nova Esperança", preco: 185000, imagem: "imgs/casa6.jpg", ativo: true }
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
