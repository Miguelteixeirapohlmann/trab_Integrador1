// Dados simulados para demonstração
let corretores = [
    { id: 1, nome: "João Silva", email: "joao@gmail.com", ativo: true },
    { id: 2, nome: "Maria Souza", email: "maria@gmail.com", ativo: false },
    { id: 3, nome: "Pedro Costa", email: "pedro@gmail.com", ativo: true }
];

// Dados simulados de imóveis
let imoveis = [
    { id: 1, nome: "Casa em Santo Antônio da Patrulha", corretorId: 1, preco: 5200000, ativo: true, imagem: "imgs/Casa1/Casa1.0.jpg", detalhes: "Casas/Casa1.php" },
    { id: 2, nome: "Casa em Taquara Alto Padrão", corretorId: 1, preco: 3000000, ativo: true, imagem: "imgs/Casa2/Casa2.0.jpg", detalhes: "Casas/Casa2.php" },
    { id: 3, nome: "Casa em Taquara Alto Padrão", corretorId: 1, preco: 3000000, ativo: true, imagem: "imgs/Casa3/Casa3.0.jpg", detalhes: "Casas/Casa3.php" },
    { id: 4, nome: "Casa em Taquara Rua Mundo Novo", corretorId: 2, preco: 170000, ativo: true, imagem: "imgs/Casa4/Casa4.0.jpg", detalhes: "Casas/Casa4.php" },
    { id: 5, nome: "Casa em Taquara Flores da Cunha", corretorId: 2, preco: 380000, ativo: true, imagem: "imgs/Casa5/Casa5.0.jpg", detalhes: "Casas/Casa5.php" },
    { id: 6, nome: "Casa em Parobé", corretorId: 2, preco: 195000, ativo: true, imagem: "imgs/Casa6/Casa6.0.jpg", detalhes: "Casas/Casa6.php" },
    { id: 7, nome: "Casa em Taquara - Bairro Santa Terezinha", corretorId: 3, preco: 650000, ativo: true, imagem: "imgs/Casa7/Casa7.0.jpg", detalhes: "Casas/Casa7.php" },
    { id: 8, nome: "Casa em Taquara - Bairro Tucanos", corretorId: 3, preco: 184900, ativo: true, imagem: "imgs/Casa8/Casa8.0.jpg", detalhes: "Casas/Casa8.php" },
    { id: 9, nome: "Casa em Taquara - Rua São Francisco", corretorId: 3, preco: 450000, ativo: true, imagem: "imgs/Casa9/Casa9.0.jpg", detalhes: "Casas/Casa9.php" }
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
            <td><a href="perfil.php?id=${corretor.id}" class="btn btn-info btn-sm">Ver Perfil</a></td>
        `;
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

// Salvar novo ou editar corretor
document.addEventListener('DOMContentLoaded', function() {
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
    
    renderCorretores();
    renderImoveis();
});
