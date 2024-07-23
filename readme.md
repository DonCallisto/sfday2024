## SymfonyDay 2024 PoC 

This is a PoC for SymfonyDay 2024. It is a simple application for leagues management.
Focus of this application is to show how you can take advantage of Symfony Framework without leaking it into business logic.
It also follows the Hexagonal Architecture (ports and adapters) principles.

This application is designed following the modular monolith principles (except for the database which is the same instance
for every module, but it's not used as an integration point) and has, also, four different bounded contexts to show another 
type of loose coupling, following DDD principles. The `Shared` BC is used to share common code and to translate events between BCs.

Implementation choices:

- All the events are handled synchronously (for sake of simplicity)
- All message handlers (identified by `<EventName>MessageHandler`) don't handle exceptions or whatsoever can go wrong.
There are different techniques to handle exceptions in message handlers (like try again to send the message if the exception, 
is something you can recover from) but they are not implemented here as we're not even saving dispatched messages in a EventStore. 
Again, this is just a PoC and is intended to be as simple as possible. All intrinsic complexity is there because I couldn't 
avoid it in order to provide something useful.

There are some books which hughly influenced this PoC:
- Implementing Domain-Driven Design by Vaughn Vernon
- Advanced Web Application Architecture by Matthias Noback

Side notes:

All code comments marked with `!!` are in place as a guide for the talk and for who read the code.

Side notes for the talk:

- abstraction (everything come to a price)
- layers (hexagonal architecture)
- eventual consistency
- differences between application use case and domain ones
- differences between use case and services

Leftovers @todos:
- implementare il listener nel BC di league per aggiornare i dati di participant
- ricontrollare struttura cartelle
- ricontrollare tutte le eccezioni non catchate
- verificare che il participant non sia disabilitato nei vari controller di league
- phpstan
- test
- deptrac