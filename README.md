# E-Commerce - Using Symfony

  * [Deployment (work only towards a Debian 10 server)](#deployment--work-only-towards-a-debian-10-server-)
      - [1. Fill the `hosts` file](#1-fill-the--hosts--file)
      - [2. Ssh ForwardAgent](#2-ssh-forwardagent)
      - [3. Launch the playbook](#3-launch-the-playbook)
      - [4. Access the app](#4-access-the-app)


This project use **Symfony** to setup an API (with the **REST** format) for an e-commerce website.
In this API you can manage **users**, **products**, **cart** and **orders**.
**Users** can register, login and access to the protected routes with an **authentication token** if the login is successful.

On top of that, this project contain an **Ansible** playbook to automatically deploy this backend on a **Debian 10** machine.

## Deployment (work only towards a Debian 10 server)

#### 1. Fill the `hosts` file

Fill the `hosts` file with your informations
<details><summary><b>Show instructions</b></summary>

```
[ecommerce]
<ip> ansible_user=<user> ansible_ssh_private_key_file=<private key location>
```

(You can change `ansible_ssh_private_key` for `ansible_ssh_pass=<password>` if you want to use password instead of ssh key)
  
</details>

#### 2. Ssh ForwardAgent

Allow Ssh to forward agent to your server for allow it to clone the private git repository.
<details><summary><b>Show instructions</b></summary>

**1. Option 1** :
   * Add in your ssh config file :
      ```
      Host ecommerce <ip>
          ForwardAgent yes
      ```
   * Add required keys using `ssh-add ~/.ssh/key-here`.

**2. Option 2** :
   * Add required keys directly on your server.
  
</details>

#### 3. Launch the playbook

Use `ansible-playbook playbook.yml -i hosts` to start the deployment on the **debian 10** server.

#### 4. Access the app

Once the ansible playbook ends, you should be able to access the app on your browser with your server's ip (port 80).
You can access the API page on `http://<ip>/api`
