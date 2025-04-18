# NYT Best Sellers Wrapper API

A Laravel‑based JSON API wrapper around the New York Times Books API

---

## 🔧 Quick Start

1. **Clone & configure**
   ```bash
   git clone <your‑repo‑url> nyt-wrapper
   cd nyt-wrapper
   cp .env.example .env
   ```

2. **Set your NYT API key** in `.env`:
   ```dotenv
   NYT_API_KEY=your_real_nyt_key_here
   ```

3. **Launch Docker stack**
   ```bash
   docker compose up -d
   ```

4. **Install dependencies & generate app key**
   ```bash
   docker compose exec php composer install --optimize-autoloader --no-dev
   docker compose exec php php artisan key:generate
   ```

5. **Cache config & routes**
   ```bash
   docker compose exec php php artisan config:cache
   docker compose exec php php artisan route:cache
   ```

6. **Test your first endpoint**
   Open in browser or Postman: 
   http://localhost:18080/api/v1/history?author=Stephen%20King
---

## 🏛️ Architecture Overview

```
app/
├─ Domain/Books/           # Entities, Repository Interfaces, Value Objects
├─ Infrastructure/         # HTTP client, Repositories, Cache decorator, Mappers
├─ UseCases/Books/         # Request/Response DTOs & Interactors
├─ Http/
│   ├─ Controllers/        # API controllers (FormRequests → Interactors → Resources)
│   ├─ Requests/           # Input validation
│   └─ Resources/          # JSON serialization
└─ Providers/
└─ RepositoryServiceProvider.php  # Binds clients, repos & decorators
```

- **Value Objects** (`ISBN`, `Offset`, `NytDate`, `ListIdentifier`) enforce data invariants.
- **Mappers** translate raw NYT JSON into domain entities.
- **CachedRepositoryDecorator** adds Redis caching around any repository.
- **Interactors** implement use‑case logic in isolation.
- **Controllers** validate requests, invoke interactors, and return JSON via Laravel Resources.
- **Routes** versioned under `/api/v1`.

---

## 🚏 API Endpoints

| Method | Path               | Query Parameters                                             | Description                                   |
|--------|--------------------|--------------------------------------------------------------|-----------------------------------------------|
| GET    | `/api/v1/lists/names` | —                                                          | List all NYT best‑seller list names           |
| GET    | `/api/v1/lists`      | `list` (slug, required)<br>`published_date`<br>`offset`    | Snapshot of a specific best‑seller list       |
| GET    | `/api/v1/history`    | one of `author`, `title`, `isbn[]` (required)<br>`offset` | Search historical best‑seller entries         |
| GET    | `/api/v1/reviews`    | one of `author`, `title`, `isbn` (required)               | Retrieve NYT editorial book reviews           |

### Response format

All endpoints return JSON matching the NYT spec, wrapped under a `data` envelope by Laravel Resources.

---

## 🧪 Testing

- **Unit tests** cover Value Objects, Mappers, Decorator, and Interactors.
- **Feature tests** cover HTTP endpoints with `Http::fake()`—no real network calls.

```bash
# Run full test suite:
docker compose exec php php artisan test
```

---

## 🔄 Caching

- Responses are cached in Redis with keys prefixed `nyt:`.
    - **1 hour** for lists (`/lists*` & `/lists/names`)
    - **15 minutes** for `/reviews`

**Flush all keys**:
```bash
docker compose exec redis redis-cli FLUSHALL
```

**Delete only NYT keys**:
```bash
docker compose exec redis redis-cli --scan --pattern 'nyt:*' | xargs redis-cli DEL
```

**Laravel cache flush**:
```bash
docker compose exec php php -r 'cache()->flush();'
```
