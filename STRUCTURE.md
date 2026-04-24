# Site Structure — Single Page Layout

Ordem das seções (de cima para baixo). Cada seção é um `<section>` semântico com `id` para navegação âncora.

## 1. Header (sticky)
- Logo (esquerda)
- Nav: Services · Why Us · Reviews · FAQ · Contact
- Botão CTA primário: **"Get a Free Quote"** (rola até o form)
- Telefone clicável `tel:` (visível em desktop)

## 2. Hero `#hero`
- Headline grande (H1)
- Sub-headline (1 frase)
- 2 CTAs: primário ("Get a Free Quote") + secundário ("Call Now")
- Background: imagem de sala limpa e ensolarada OU gradiente + foto à direita
- Mini trust bar logo abaixo: *"Licensed · Insured · 5★ Google Reviews · Serving Tampa since YYYY"*

## 3. Services `#services`
- Título: "Cleaning Services We Offer"
- Grid de 6 cards (ícone + título + 1 linha de descrição + link "Learn more")
- Serviços listados no CONTENT.md

## 4. Why Choose Us `#why`
- Título: "Why Tampa Homeowners Choose Lino"
- Grid 2x3 de benefícios com ícones (Licensed & Insured, Background-checked team, Eco-friendly options, Flexible scheduling, 100% satisfaction guarantee, Upfront pricing)

## 5. How It Works `#how`
- 3 passos numerados:
  1. Request a free quote
  2. We schedule at your convenience
  3. Relax — your home gets that sparkle

## 6. Service Area `#area`
- Título: "Proudly Serving the Tampa Bay Area"
- Lista de bairros atendidos (chips)
- Embed de Google Maps (opcional) centrado em Tampa com raio
- Texto: *"Don't see your neighborhood? Give us a call — we might still cover you."*

## 7. Testimonials `#reviews`
- Slider/grid de 3 cards (nome, bairro, estrelas, review)
- Placeholder até cliente mandar os reais
- Badge: "5.0 ★ on Google"

## 8. FAQ `#faq`
- Accordion com 6 perguntas (detalhadas no CONTENT.md)
- Ajuda SEO com long-tail keywords

## 9. Contact / Quote `#contact`
- Título: "Get Your Free Quote in Minutes"
- Layout 2 colunas:
  - Esquerda: texto curto + telefone + email + horários + endereço
  - Direita: formulário (campos no CONTENT.md)
- Botão: "Send My Quote Request"
- Mensagem de sucesso após envio

## 10. Footer
- Logo + slogan
- Quick links (Services, About, FAQ, Contact)
- Contato: phone, email, hours
- Service area mini-list
- Social icons (Facebook, Instagram, Google)
- Copyright + "Licensed, Bonded & Insured"

## Elementos flutuantes (mobile)
- Botão "Call Now" fixo no bottom (só mobile)
- Botão secundário WhatsApp/SMS (opcional)

## Navegação interna
- Todos os links do menu levam a âncoras (#)
- Smooth scroll via CSS `scroll-behavior: smooth`
- Scrollspy simples em JS (opcional)
