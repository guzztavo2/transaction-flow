export class User {
    private name: string;
    private email: string;

    constructor(name: string, email: string) {
        this.name = name;
        this.email = email;
    }

    getName(): string {
        return this.name;
    }

    getEmail(): string {
        return this.email;
    }

    setName(name: string): void {
        this.name = name;
    }

    setEmail(email: string): void {
        this.email = email;
    }
}