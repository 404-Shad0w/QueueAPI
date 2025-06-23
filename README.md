---

# 🕒 QueueAPI para PocketMine-MP

**QueueAPI** es una API estática y sencilla para gestionar múltiples colas de jugadores. Ideal para sistemas de duelos, emparejamientos, minijuegos, FFA, lobbies y cualquier sistema por turnos en servidores **PocketMine-MP**.

---

## 📦 Características

- ✅ API completamente **estática**, sin necesidad de instancias.
- 🔁 Soporte para múltiples colas con nombres personalizados.
- 🔢 Asignación automática de posición al ingresar.
- 🧹 Reorganización automática al remover jugadores.
- ⚙️ Tamaño máximo configurable para todas las colas.
- 📥 Control total de jugadores en cola (`add`, `remove`, `next`, etc).

---

## 🛠️ Instalación

1. Coloca `QueueAPI.php` en el directorio `src/QueueAPI/` de tu plugin.
2. Importa la clase donde la vayas a usar:

```php
use QueueAPI\QueueAPI;
```

---

## ⚙️ Configuración opcional

Puedes definir un límite global de tamaño máximo para las colas (por defecto es 100):

```php
QueueAPI::setMaxQueueSize(200);
```

---

# 🧠 Métodos disponibles

| Método                                                | Tipo        | Descripción                                                                 |
|--------------------------------------------------------|-------------|-----------------------------------------------------------------------------|
| `setMaxQueueSize(int $max)`                           | `void`      | Define el tamaño máximo global para todas las colas.                        |
| `add(string $player, string $queue)`                  | `bool`      | Añade un jugador a una cola. Devuelve `false` si ya está o si está llena.  |
| `remove(string $player, string $queue)`               | `bool`      | Elimina un jugador de una cola y reorganiza posiciones automáticamente.    |
| `getPosition(string $player, string $queue)`          | `int`       | Devuelve la posición del jugador en la cola, o `0` si no está.             |
| `next(string $queue)`                                 | `?string`   | Elimina y retorna al jugador en primera posición. Devuelve `null` si vacía.|
| `size(string $queue)`                                 | `int`       | Retorna el número de jugadores en una cola específica.                     |
| `getPlayers(string $queue)`                           | `string[]`  | Devuelve la lista ordenada de jugadores en una cola.                       |
| `getQueues()`                                         | `string[]`  | Devuelve los nombres de todas las colas activas.                           |

---

# 💡 Ejemplo de uso

```php
use QueueAPI\QueueAPI;

QueueAPI::setMaxQueueSize(150);

QueueAPI::add("Player1", "duels");
QueueAPI::add("Player2", "duels");

$pos = QueueAPI::getPosition("Player2", "duels"); // Devuelve 2

$turno = QueueAPI::next("duels"); // Devuelve "Player1" y lo remueve de la cola

QueueAPI::remove("Player2", "duels"); // Elimina a Player2 de la cola

$jugadores = QueueAPI::getPlayers("duels"); // []
```

---

# 🧩 Ejemplo para plugin PocketMine

```php
public function onEnable(): void {
    QueueAPI\QueueAPI::setMaxQueueSize(100);
}

public function onCommandJoinQueue(string $player, string $queue): void {
    if (QueueAPI\QueueAPI::add($player, $queue)) {
        $pos = QueueAPI\QueueAPI::getPosition($player, $queue);
        $this->getServer()->getPlayerExact($player)?->sendMessage("📥 Te uniste a la cola '$queue'. Posición: $pos");
    } else {
        $this->getServer()->getPlayerExact($player)?->sendMessage("❌ No pudiste unirte. Ya estás en la cola o está llena.");
    }
}
```

---

# 📁 Estructura interna (ejemplo)

```php
[
  "duels" => [
    "Player1" => 1,
    "Player2" => 2
  ],
  "ranked" => [
    "Player3" => 1
  ]
]
```

Este sistema es **en memoria**, no persistente. Si deseas guardarlo, puedes integrarlo fácilmente con `Config`.

---

# ✅ Buenas prácticas

- Usa nombres simples para las colas (`duels`, `ranked`, `ffa`).
- Verifica siempre si `add()` retorna `false` para evitar errores silenciosos.
- Llama a `next()` al iniciar una partida o turno.
- Combínalo con tus sistemas de arenas, matchmaking o emparejamientos personalizados.

---

# 📜 Licencia

Este código es **open source**. Puedes utilizarlo, modificarlo o redistribuirlo libremente. Si decides dar créditos, ¡serán bien recibidos! 🎉

---