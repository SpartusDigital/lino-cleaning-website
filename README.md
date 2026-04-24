# Lino Professional Cleaning Services — Website Project

Pasta de briefing completo para desenvolver o site da **Lino Professional Cleaning Services** (Tampa, FL) usando o **Claude Code via CMD**.

---

## Resumo do projeto

- **Cliente:** Lino Professional Cleaning Services
- **Tipo de negócio:** House cleaning / residential cleaning service
- **Localização:** Tampa, Florida — USA (atende toda a Tampa Bay)
- **Tipo de site:** Landing page estática (HTML + CSS + JavaScript + PHP form handler)
- **Idioma:** Inglês (US)
- **Funcionalidade principal:** Formulário de orçamento com envio por email (PHP `mail()` no Hostinger)
- **Hospedagem:** **Hostinger** (domínio + hospedagem já contratados pela cliente)

---

## Estrutura da pasta

```
lino-cleaning-website/
├── README.md                   <- você está aqui
├── BRIEF.md                    <- brief completo (preços reais + área de atendimento)
├── STRUCTURE.md                <- estrutura do site seção por seção
├── CONTENT.md                  <- todo o copy em inglês pronto + tabela de preços
├── DESIGN.md                   <- paleta de cores, tipografia, estilo visual
├── SEO.md                      <- palavras-chave + meta tags para Tampa Bay
├── ASSETS.md                   <- lista das imagens reais baixadas do Drive
├── HOSTINGER_DEPLOY.md         <- passo-a-passo pra publicar no Hostinger
├── prompts/
│   └── INITIAL_PROMPT.md       <- prompt pronto para colar no Claude Code
├── assets/                     <- 11 imagens reais da Lino (logo + fotos de trabalho)
└── reference/                  <- coloque aqui sites de referência, prints
```

---

## Como usar com Claude Code (CMD)

1. **Copie esta pasta inteira para o Desktop.**
   No Windows, use:
   ```cmd
   xcopy /E /I "caminho-da-pasta" "%USERPROFILE%\Desktop\lino-cleaning-website"
   ```

2. **Abra o CMD dentro da pasta:**
   ```cmd
   cd %USERPROFILE%\Desktop\lino-cleaning-website
   ```

3. **Logo e fotos já estão prontos** em `assets/` (11 arquivos baixados do Drive da Spartus).

4. **Inicie o Claude Code:**
   ```cmd
   claude
   ```

5. **Cole o prompt inicial** (conteúdo de `prompts/INITIAL_PROMPT.md`).
   O Claude Code irá ler os demais arquivos `.md` automaticamente e construir o site (HTML + CSS + JS + PHP).

6. **Iteração:** peça ajustes conversando normalmente com o Claude.
   Ex: *"troque a cor primária por verde água"*, *"adicione seção de FAQ"*.

7. **Deploy no Hostinger:** siga o passo-a-passo em `HOSTINGER_DEPLOY.md` (upload via File Manager ou FTP, configurar email no painel, testar formulário).

---

## Checklist antes de começar

- [x] Logo (`logo.png`) — já no assets/
- [x] 10 fotos reais de trabalho — já no assets/
- [x] Área de atendimento definida (13 cidades da Tampa Bay — ver BRIEF.md)
- [x] Tabela de preços residencial, deep e pós-construção (ver CONTENT.md)
- [ ] Confirmar telefone e email da cliente (substituir placeholders)
- [ ] Confirmar horário de funcionamento oficial
- [ ] Cliente tem Google Business Profile? (pode criar depois)
- [ ] Pegar credenciais Hostinger (FTP ou File Manager) pra deploy

---

## Próximos passos sugeridos (depois do site no ar)

1. **Deploy no Hostinger** (ver `HOSTINGER_DEPLOY.md`).
2. **Criar email profissional** `hello@linocleaningtampa.com` no painel Hostinger (grátis com plano de hospedagem).
3. **Testar o formulário PHP** enviando um lead de teste.
4. **Criar/otimizar Google Business Profile** da cliente — essencial pra ranquear no mapa.
5. **Cadastrar em diretórios locais**: Yelp, Angi, Thumbtack, Nextdoor, HomeAdvisor.
6. **Instalar Google Analytics 4 + Meta Pixel** pra rastrear leads.
7. **Pedir reviews no Google** — mandar link pros primeiros clientes após o serviço.
