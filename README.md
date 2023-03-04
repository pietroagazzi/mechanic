# Mechanic

[![Makefile CI](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml/badge.svg)](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml)
[![codecov](https://codecov.io/gh/pietroagazzi/mechanic/branch/main/graph/badge.svg?token=ZBZIGLRZVH)](https://codecov.io/gh/pietroagazzi/mechanic)
🇮🇹 Mechanic ha come obiettivo quello di ricreare le funzioni principali dei framework PHP più grandi, con particolare riferimento a [Symfony](http://symfony.com/). In parole semplici, si tratta di un micro-framework che sfrutta la logica e l'implementazione dei moduli principali di Symfony per aiutare a capirlo meglio. Tieni presente che Mechanic non è pensato per essere utilizzato in produzione, ma è solo un progetto di studio creato da uno studente troppo curioso.

🇬🇧 Mechanic aims to recreate the main functions of larger PHP frameworks, with particular reference to [Symfony](http://symfony.com/). In simple terms, it is a micro-framework that leverages the logic and implementation of the main modules of Symfony to help understand it better. Note that Mechanic is not intended to be used in production, but is just a study project created by a curious student.

---


# Stories

Ho iniziato lo sviluppo di Mechanic parallelamente allo studio di Symfony, partendo dal Kernel. Di seguito riporto dettagliatamente ogni nuovo argomento che ho imparato e tutti i problemi che ho affrontato.

## How Symfony Works?

Prima di partire, è importante comprendere il funzionamento di Symfony. Si tratta di un framework web PHP che segue il pattern di sviluppo Model-View-Controller (MVC). I suoi principali componenti sono:

1. **Kernel**: rappresenta il nucleo del framework.
2. **Routing**: gestisce le rotte.
3. **Controller**: si occupa di gestire le richieste, i moduli e le viste.
4. **Templating**: permette di separare la logica dell'applicazione dalla presentazione dell'interfaccia utente.
5. **Form**: consente di creare e gestire i form HTML.
6. **Doctrine**: Object-Relation Mapper (ORM), interfaccia per l'accesso al database e alle entità.
7. **Security**: sistema di sicurezza e autorizzazione degli utenti.
8. **Event Dispatcher**: creazione e gestione di eventi e ascoltatori.

Questi sono solo i principali componenti di Symfony. Nel nostro progetto, ci concentreremo sulla ricreazione semplificata di ognuno di essi, mantenendone la logica di base originale.

# Note

Un'applicazione Symfony è costruita con componenti, mentre Mechanic integra solo i componenti fondamentali, inserendoli direttamente nell'applicazione. Questo lo rende una libreria più che un framework, ma in questo modo la lettura e la comprensione del codice e del funzionamento viene ulteriormente semplificata.

---

# Http

[The HttpFoundation Component (Symfony Docs)](https://symfony.com/doc/current/components/http_foundation.html#response)

Sebbene il core di Symfony - così come per Laravel - sia il [Kernel](https://www.notion.so/Mechanic-a89e7945f6bc43d68c2aa99e0e5e799a), inizieremo a sviluppare Mechanic partendo ricostruendo il componente *[HttpFoundation](https://symfony.com/doc/current/components/http_foundation.html)* di Symfony. Http Foundation mette a disposizione tre classi principali: *Request,* *Response* e *Session.* Queste due classi non sono altro che una rappresentazione delle specifiche HTTP.