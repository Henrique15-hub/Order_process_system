1. Sistema de processamento de pedidos — minha escolha

Um backend de e-commerce sem precisar construir o frontend.

Fluxo:

Cliente
   ↓
Cria pedido
   ↓
Reserva estoque
   ↓
Processa pagamento
   ↓
Confirma pedido
   ↓
Envia para processamento 

Você poderia implementar:

autenticação com Sanctum
produtos e estoque
carrinho
pedidos
cupons
cálculo de frete
integração com uma API externa
webhook
filas (Queue)
eventos (Events)
notificações
Redis
controle de concorrência no estoque
transações SQL
testes automatizados
Docker
