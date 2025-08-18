// Dados simulados para demonstração
let corretores = [
    { id: 1, nome: "João Silva", email: "joao@email.com", ativo: true },
    { id: 2, nome: "Maria Souza", email: "maria@email.com", ativo: false },
    { id: 3, nome: "Carlos Lima", email: "carlos@email.com", ativo: true }
];

let imoveis = [
    { id: 1, nome: "Casa Realengo", corretorId: 1, preco: 250000, imagem: "imgs/Foto9.jpg", ativo: true, detalhes: "Casas/Casa1.html" },
    { id: 2, nome: "Casa Alto Rolantinho", corretorId: 2, preco: 180000, imagem: "imgs/foto8.jpg", ativo: true, detalhes: "Casas/Casa2.html" },
    { id: 3, nome: "Casa Alpha Ville", corretorId: 3, preco: 150000, imagem: "imgs/foto7.jpg", ativo: true, detalhes: "Casas/Casa3.html" },
    { id: 4, nome: "Casa Jardim das Flores", corretorId: 1, preco: 220000, imagem: "imgs/foto1.jpeg", ativo: true, detalhes: "Casas/Casa4.html" },
    { id: 5, nome: "Casa Vista Alegre", corretorId: 2, preco: 310000, imagem: "imgs/foto2.jpg", ativo: true, detalhes: "Casas/Casa5.html" },
    { id: 6, nome: "Casa Solar dos Pássaros", corretorId: 3, preco: 275000, imagem: "imgs/foto3.jpg", ativo: true, detalhes: "Casas/Casa6.html" },
    { id: 7, nome: "Casa Bela Vista", corretorId: 1, preco: 199000, imagem: "imgs/foto4.jpg", ativo: true, detalhes: "Casas/Casa7.html" },
    { id: 8, nome: "Casa do Gabriel", corretorId: 2, preco: 1, imagem: "imgs/foto5.jpg", ativo: true, detalhes: "Casas/Casa8.html" },
    { id: 9, nome: "Casa Nova Esperança", corretorId: 3, preco: 185000, imagem: "imgs/casa6.jpg", ativo: true, detalhes: "Casas/Casa9.html" }
];

function renderCorretores() {
    const tbody = document.querySelector("#corretoresTable tbody");
    tbody.innerHTML = "";
    corretores.forEach(corretor => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${corretor.nome}</td>
            <td>${corretor.email}</td>
            <td><span class="badge ${corretor.ativo ? 'bg-success' : 'bg-secondary'}">${corretor.ativo ? 'Ativo' : 'Desabilitado'}</span></td>
            <td>
                <button class="btn btn-primary btn-sm me-1" onclick="editCorretor(${corretor.id})"><i class='fas fa-edit'></i> Editar</button>
                <button class="btn btn-sm ${corretor.ativo ? 'btn-warning' : 'btn-success'}" onclick="toggleCorretor(${corretor.id})">${corretor.ativo ? 'Desabilitar' : 'Habilitar'}</button>
            </td>
            <td><a href="corretor-${corretor.id}.html" class="btn btn-info btn-sm">Ver Perfil</a></td>
        `;
// Abrir modal para novo corretor
window.openCorretorModal = function() {
    document.getElementById('corretorId').value = '';
    document.getElementById('corretorNome').value = '';
    document.getElementById('corretorEmail').value = '';
    document.getElementById('corretorStatus').value = 'true';
    document.getElementById('modalCorretorLabel').innerText = 'Novo Corretor';
}

// Abrir modal para editar corretor
window.editCorretor = function(id) {
    const corretor = corretores.find(c => c.id === id);
    if (corretor) {
        document.getElementById('corretorId').value = corretor.id;
        document.getElementById('corretorNome').value = corretor.nome;
        document.getElementById('corretorEmail').value = corretor.email;
        document.getElementById('corretorStatus').value = corretor.ativo ? 'true' : 'false';
        document.getElementById('modalCorretorLabel').innerText = 'Editar Corretor';
        var modal = new bootstrap.Modal(document.getElementById('modalCorretor'));
        modal.show();
    }
}

// Salvar novo ou editar corretor
document.getElementById('corretorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('corretorId').value;
    const nome = document.getElementById('corretorNome').value;
    const email = document.getElementById('corretorEmail').value;
    const ativo = document.getElementById('corretorStatus').value === 'true';
    if (id) {
        // Editar
        const corretor = corretores.find(c => c.id == id);
        if (corretor) {
            corretor.nome = nome;
            corretor.email = email;
            corretor.ativo = ativo;
        }
    } else {
        // Novo
        const novoId = corretores.length ? Math.max(...corretores.map(c => c.id)) + 1 : 1;
        corretores.push({ id: novoId, nome, email, ativo });
    }
    renderCorretores();
    var modal = bootstrap.Modal.getInstance(document.getElementById('modalCorretor'));
    modal.hide();
});
        tbody.appendChild(tr);
    });
}

function renderImoveis() {
    const tbody = document.querySelector("#imoveisTable tbody");
    tbody.innerHTML = "";
    imoveis.forEach(imovel => {
        const corretor = corretores.find(c => c.id === imovel.corretorId);
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td><img src="${imovel.imagem}" class="product-img-preview" style="max-width:80px;max-height:60px;"></td>
            <td>${imovel.nome}</td>
            <td>${corretor ? corretor.nome : '---'}</td>
            <td>R$ ${imovel.preco.toLocaleString('pt-BR')}</td>
            <td><span class="badge ${imovel.ativo ? 'bg-success' : 'bg-secondary'}">${imovel.ativo ? 'Disponível' : 'Desabilitado'}</span></td>
            <td>
                <a href="${imovel.detalhes}" class="btn btn-sm btn-primary mb-1">Ver detalhes</a><br>
                <button class="btn btn-sm ${imovel.ativo ? 'btn-warning' : 'btn-success'}" onclick="toggleImovel(${imovel.id})">${imovel.ativo ? 'Desabilitar' : 'Habilitar'}</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

window.toggleCorretor = function(id) {
    const corretor = corretores.find(c => c.id === id);
    if (corretor) {
        corretor.ativo = !corretor.ativo;
        renderCorretores();
    }
}

window.toggleImovel = function(id) {
    const imovel = imoveis.find(i => i.id === id);
    if (imovel) {
        imovel.ativo = !imovel.ativo;
        renderImoveis();
    }
}

renderCorretores();
renderImoveis();
