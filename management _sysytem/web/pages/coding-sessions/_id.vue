<template>
  <div>
    <!-- Loading -->
    <div v-if="isLoading" class="loader">
      <div class="spinner-border text-primary" role="loader">
        <span class="sr-only"></span>
      </div>
    </div>
    <!-- Instruction -->
    <div v-if="!isStartedCoding && !isLoading && isValidSession">
      <div
        style="
          width: 50%;
          height: 100vh;
          position: fixed;
          top: 0;
          bottom: 0;
          background: #21252908;
        "
      >
        <div>
          <div style="padding: 42px">
            <div>
              <div style="font-size: 32px; font-weight: bold">Welcome.</div>
              <div>
                Please read all instructions carefully before start the coding
                challenge.
                <div style="padding-top: 12px">
                  <ul>
                    <li>
                      After pressing the start button your have 30 minutes to
                      write and submit your code.
                    </li>
                    <li>
                      Once you start the coding challenge, your left side panel
                      challenge description and right side panel where you write
                      your code.
                    </li>
                    <li>
                      You can write your code in
                      <b>C, C++, JAVA, PYTHON, JS, GO, and PHP.</b>
                    </li>
                    <li>
                      Your code panel bottom side you can also run your program
                      with inputs and output.
                    </li>
                    <li>
                      Please do not change your coding challenge program
                      template.
                    </li>
                    <li>
                      Once you submit your coding challenge, it will be
                      immutable and it will consider as your submission.
                    </li>
                    <li>Happy Coding :)</li>
                  </ul>
                </div>
              </div>
              <div style="margin-top: 52px">
                <button
                  v-if="!isStartedChallengeLoading"
                  id="startCoding"
                  class="btn btn-primary btn-lg"
                  @click="startCoding"
                >
                  Okay, Let's Start
                </button>
                <div v-if="isStartedChallengeLoading" class="spinner-border">
                  <span class="sr-only"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Coding Area -->
    <div v-if="!isLoading && isStartedCoding && isValidSession">
      <header
        style="
          width: 100%;
          height: 52px;
          background: #fff;
          padding: 0 18px;
          box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.5);
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >
        <div style="font-size: 18px; font-weight: bold">Apricot</div>
        <div style="display: flex">
          <div>
            <div>
              <b-dropdown
                id="languages"
                :text="language.charAt(0).toUpperCase() + language.slice(1)"
                class="m-md-2"
                :aria-disabled="isRunning || isSubmitting"
              >
                <b-dropdown-item
                  :active="language == 'objective-c'"
                  @click="changeLanguage('objective-c')"
                  >C</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'cpp'"
                  @click="changeLanguage('cpp')"
                  >CPP</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'java'"
                  @click="changeLanguage('java')"
                  >Java</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'python'"
                  @click="changeLanguage('python')"
                  >Python</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'go'"
                  @click="changeLanguage('go')"
                  >Go</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'javascript'"
                  @click="changeLanguage('javascript')"
                  >Javascript</b-dropdown-item
                >
                <b-dropdown-item
                  :active="language == 'php'"
                  @click="changeLanguage('php')"
                  >PHP</b-dropdown-item
                >
              </b-dropdown>
            </div>
          </div>
          <button
            v-if="!isSubmitted"
            id="run"
            class="btn btn-info m-md-2"
            :disabled="isRunning || isSubmitting"
            @click="run"
          >
            {{ isRunning ? "Running" : "Run" }}
          </button>
          <button
            id="submit"
            class="btn btn-success m-md-2"
            :disabled="isRunning || isSubmitting || isSubmitted"
            @click="submit"
          >
            {{
              !isSubmitted
                ? isSubmitting
                  ? "Submitting"
                  : "Submit"
                : "Submitted"
            }}
          </button>
        </div>
      </header>
      <main style="width: 100%; position: fixed; top: 58px; bottom: 0">
        <div style="width: 100%; height: 100%; display: flex">
          <!-- Description -->
          <div style="width: 33.33%; background: #f6f9fc" class="overflow-auto">
            <div style="padding: 18px">
              <div
                id="title"
                style="font-size: 18px; font-weight: bold; color: #000000d1"
              >
                {{ challegeTitle }}
              </div>
              <div id="description" style="padding: 22px 0">
                <!-- eslint-disable -->
                <div v-html="challengeInstructions"></div>
              </div>
            </div>
          </div>
          <!-- Playground -->
          <div style="width: 66.66%; height: 100%;" class="overflow-auto">
            <!-- Editor -->
            <div>
              <MonacoEditor
                v-model="code"
                style="width: 100%; height: calc(100vh - 250px);"
                :options="editorOptions"
                :language="language"
              />
            </div>
            <!-- debugger -->
            <div style="padding: 0 12px;">
              <b-tabs content-class="mt-3">
                <b-tab title="Inputs" active>
                  <div class="form-group">
                    <textarea
                      id="inputs"
                      v-model="inputs"
                      class="form-control"
                      rows="3"
                      :disabled="isRunning || isSubmitting || isSubmitted"
                    ></textarea>
                  </div>
                </b-tab>
                <b-tab title="Output">
                  <div class="form-group">
                    <textarea
                      id="output"
                      v-model="output"
                      class="form-control"
                      rows="3"
                      :disabled="isRunning || isSubmitting || isSubmitted"
                    ></textarea>
                  </div>
                </b-tab>
                <b-tab title="Debugger" title-link-class="text-danger">
                  <div class="form-group">
                    <pre id="debbuger" style="color: red;">{{err}}</pre>
                  </div>
                </b-tab>
              </b-tabs>
            </div>
          </div>
        </div>
      </main>
    </div>
    <!-- Invalid Session -->
    <div
      v-if="!isValidSession && !isLoading"
      style="
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
"
    >
      <div
        style="
          width: 100%;
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
"
      >
        <div style="display: block;">
          <div
            style="display: flex; align-items: center; justify-content: center;"
          >
            <svg
              id="bac3cfc7-b61b-48ce-8441-8100e40ddaa6"
              data-name="Layer 1"
              xmlns="http://www.w3.org/2000/svg"
              width="200"
              height="200"
              viewBox="0 0 797.5 834.5"
            >
              <title>void</title>
              <ellipse
                cx="308.5"
                cy="780"
                rx="308.5"
                ry="54.5"
                fill="#3f3d56"
              />
              <circle cx="496" cy="301.5" r="301.5" fill="#3f3d56" />
              <circle cx="496" cy="301.5" r="248.89787" opacity="0.05" />
              <circle cx="496" cy="301.5" r="203.99362" opacity="0.05" />
              <circle cx="496" cy="301.5" r="146.25957" opacity="0.05" />
              <path
                d="M398.42029,361.23224s-23.70394,66.72221-13.16886,90.42615,27.21564,46.52995,27.21564,46.52995S406.3216,365.62186,398.42029,361.23224Z"
                transform="translate(-201.25 -32.75)"
                fill="#d0cde1"
              />
              <path
                d="M398.42029,361.23224s-23.70394,66.72221-13.16886,90.42615,27.21564,46.52995,27.21564,46.52995S406.3216,365.62186,398.42029,361.23224Z"
                transform="translate(-201.25 -32.75)"
                opacity="0.1"
              />
              <path
                d="M415.10084,515.74682s-1.75585,16.68055-2.63377,17.55847.87792,2.63377,0,5.26754-1.75585,6.14547,0,7.02339-9.65716,78.13521-9.65716,78.13521-28.09356,36.8728-16.68055,94.81576l3.51169,58.82089s27.21564,1.75585,27.21564-7.90132c0,0-1.75585-11.413-1.75585-16.68055s4.38962-5.26754,1.75585-7.90131-2.63377-4.38962-2.63377-4.38962,4.38961-3.51169,3.51169-4.38962,7.90131-63.2105,7.90131-63.2105,9.65716-9.65716,9.65716-14.92471v-5.26754s4.38962-11.413,4.38962-12.29093,23.70394-54.43127,23.70394-54.43127l9.65716,38.62864,10.53509,55.3092s5.26754,50.04165,15.80262,69.356c0,0,18.4364,63.21051,18.4364,61.45466s30.72733-6.14547,29.84941-14.04678-18.4364-118.5197-18.4364-118.5197L533.62054,513.991Z"
                transform="translate(-201.25 -32.75)"
                fill="#2f2e41"
              />
              <path
                d="M391.3969,772.97846s-23.70394,46.53-7.90131,48.2858,21.94809,1.75585,28.97148-5.26754c3.83968-3.83968,11.61528-8.99134,17.87566-12.87285a23.117,23.117,0,0,0,10.96893-21.98175c-.463-4.29531-2.06792-7.83444-6.01858-8.16366-10.53508-.87792-22.826-10.53508-22.826-10.53508Z"
                transform="translate(-201.25 -32.75)"
                fill="#2f2e41"
              />
              <path
                d="M522.20753,807.21748s-23.70394,46.53-7.90131,48.28581,21.94809,1.75584,28.97148-5.26754c3.83968-3.83969,11.61528-8.99134,17.87566-12.87285a23.117,23.117,0,0,0,10.96893-21.98175c-.463-4.29531-2.06792-7.83444-6.01857-8.16367-10.53509-.87792-22.826-10.53508-22.826-10.53508Z"
                transform="translate(-201.25 -32.75)"
                fill="#2f2e41"
              />
              <circle
                cx="295.90488"
                cy="215.43252"
                r="36.90462"
                fill="#ffb8b8"
              />
              <path
                d="M473.43048,260.30832S447.07,308.81154,444.9612,308.81154,492.41,324.62781,492.41,324.62781s13.70743-46.39439,15.81626-50.61206Z"
                transform="translate(-201.25 -32.75)"
                fill="#ffb8b8"
              />
              <path
                d="M513.86726,313.3854s-52.67543-28.97148-57.943-28.09356-61.45466,50.04166-60.57673,70.2339,7.90131,53.55335,7.90131,53.55335,2.63377,93.05991,7.90131,93.93783-.87792,16.68055.87793,16.68055,122.90931,0,123.78724-2.63377S513.86726,313.3854,513.86726,313.3854Z"
                transform="translate(-201.25 -32.75)"
                fill="#d0cde1"
              />
              <path
                d="M543.2777,521.89228s16.68055,50.91958,2.63377,49.16373-20.19224-43.89619-20.19224-43.89619Z"
                transform="translate(-201.25 -32.75)"
                fill="#ffb8b8"
              />
              <path
                d="M498.50359,310.31267s-32.48318,7.02339-27.21563,50.91957,14.9247,87.79237,14.9247,87.79237l32.48318,71.11182,3.51169,13.16886,23.70394-6.14547L528.353,425.32067s-6.14547-108.86253-14.04678-112.37423A33.99966,33.99966,0,0,0,498.50359,310.31267Z"
                transform="translate(-201.25 -32.75)"
                fill="#d0cde1"
              />
              <polygon
                points="277.5 414.958 317.885 486.947 283.86 411.09 277.5 414.958"
                opacity="0.1"
              />
              <path
                d="M533.896,237.31585l.122-2.82012,5.6101,1.39632a6.26971,6.26971,0,0,0-2.5138-4.61513l5.97581-.33413a64.47667,64.47667,0,0,0-43.1245-26.65136c-12.92583-1.87346-27.31837.83756-36.182,10.43045-4.29926,4.653-7.00067,10.57018-8.92232,16.60685-3.53926,11.11821-4.26038,24.3719,3.11964,33.40938,7.5006,9.18513,20.602,10.98439,32.40592,12.12114,4.15328.4,8.50581.77216,12.35457-.83928a29.721,29.721,0,0,0-1.6539-13.03688,8.68665,8.68665,0,0,1-.87879-4.15246c.5247-3.51164,5.20884-4.39635,8.72762-3.9219s7.74984,1.20031,10.062-1.49432c1.59261-1.85609,1.49867-4.559,1.70967-6.99575C521.28248,239.785,533.83587,238.70653,533.896,237.31585Z"
                transform="translate(-201.25 -32.75)"
                fill="#2f2e41"
              />
              <circle cx="559" cy="744.5" r="43" fill="#6c63ff" />
              <circle cx="54" cy="729.5" r="43" fill="#6c63ff" />
              <circle cx="54" cy="672.5" r="31" fill="#6c63ff" />
              <circle cx="54" cy="624.5" r="22" fill="#6c63ff" />
            </svg>
          </div>
          <h5 class="text-center" style="padding-top: 52px;">
            Invalid session code!
          </h5>
          <div>
            <p style="font-size: 13px; text-align: center;">
              Make sure entered the session is given by the improwised technologies.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import marked from "marked";
import axios from "axios";

import python from "@/static/templates/python.txt";
import javascript from "@/static/templates/javascript.txt";
import go from "@/static/templates/go.txt";
import cpp from "@/static/templates/cpp.txt";
import c from "@/static/templates/c.txt";
import php from "@/static/templates/php.txt";
import java from "@/static/templates/java.txt";

export default {
  data() {
    return {
      /// ////  LOADERS  ////////
      isLoading: true,
      isRunning: false,
      isSubmitting: false,
      isStartedChallengeLoading: false,
      /// ////  CODE SESSION  ////////
      isValidSession: false,
      isStartedCoding: false,
      isSubmitted: false,
      sessionId: null,
      /// ////  CODE PLAYGOUND  ////////
      code: null,
      language: "python",
      inputs: null,
      output: null,
      err: "Error will appear here..",
      /// ////  CHALLENGE RELATED  ////////
      challegeTitle: null,
      challengeInstructions: null,
      /// ////  MONACO EDITOR RELATED  ////////
      editorOptions: {
        minimap: {
          enabled: false,
        },
        fontFamily: "JetBrains Mono",
      },
      /// ////  CODE PLAYGOUND & PERSISTANCE ////////
      lastRunnedCode: null,
      lastRunnedLanguage: null,
      accessToken: null,
    };
  },

  mounted() {
    this.isLoading = true;
    this.sessionId = this.$route.params.id;
    // Fetch session
    this.fetchChallenge();
  },

  methods: {
    /**
    Fetch the challenge using session id and x-auth-id  
    */
    async fetchChallenge() {
      this.isLoading = true;

      // Fetch challenge and valid session
      try {
        if (
          process.env.MODE !== "development" &&
          process.env.MODE !== "testing"
        ) {
          const res = await axios.get(`/oauth2/auth`);
          this.accessToken = res.headers["x-auth-request-access-token"];
        }

        let session = await axios.post(
          `/api/v1/coding-sessions/fetch-session/?session_id=${this.sessionId}`,
          {},
          {
            headers: {
              "x-access-token": this.accessToken,
            },
          }
        );

        if (!session) {
          this.$toasted.show("Something went wrong, Please reload your page!", {
            theme: "toasted-primary",
            type: "error",
            position: "bottom-right",
            duration: 5000,
          });

          return;
        }

        session = session.data;

        if (!session.success)
          this.$toasted.show("Something went wrong, Please reload your page!", {
            theme: "toasted-primary",
            type: "error",
            position: "bottom-right",
            duration: 5000,
          });

        this.isValidSession = true;

        if (!session.is_started) {
          this.isStartedCoding = false;
          this.isLoading = false;

          return;
        }

        this.isStartedCoding = true;

        // Temporal
        // Session started and session is expired and no submission are found
        if (session.is_expired || session.is_submitted) {
          this.isSubmitted = true;
          // If empty submission happen
          this.code = session.playground.code || python;
          this.language = session.playground.language || "python";
          this.challegeTitle = session.data.title;
          this.challengeInstructions = marked(session.data.description);
          this.inputs = session.data.inputs;
          this.output = session.data.output;
          this.$toasted.show("Already, You have submitted your code!", {
            position: "bottom-left",
          });
        } else {
          // Session not expired;
          this.isSubmitted = false;

          // Initially no code written
          if (!session.playground.code && !session.playground.language) {
            this.language = "python";
            this.code = python;
          } else {
            this.code = session.playground.code;
            this.language = session.playground.language;
          }

          this.challegeTitle = session.data.title;
          this.challengeInstructions = marked(session.data.description);
          this.inputs = session.data.inputs;
          this.output = session.data.output;
        }

        this.isLoading = false;
      } catch (e) {
        // If invalid session
        if (!e.response.data.success && !e.response.data.is_valid) {
          this.isLoading = false;
          this.isValidSession = false;
        }
      }
    },

    /**
     * User read instructions and press start coding challenge
     */
    async startCoding() {
      this.isStartedChallengeLoading = true;

      try {
        let session = await axios.post(
          `/api/v1/coding-sessions/fetch-session/?session_id=${this.sessionId}&start=true`,
          {},
          {
            headers: {
              "x-access-token": this.accessToken,
            },
          }
        );

        session = session.data;

        if (session.is_started) {
          this.isStartedChallengeLoading = false;
          this.fetchChallenge();
        }
      } catch (e) {
        this.$toasted.show("Something went wrong, Please reload your page!", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      }
    },

    /** 
      Fire when any language selected from dropdown
    */
    changeLanguage(language) {
      // change language if program is not running, is not submitting, had not submitted
      if (!this.isRunning && !this.isSubmitting && !this.isSubmitted) {
        this.language = language;

        /// User see template at the end go their template
        if (this.language === this.lastRunnedLanguage) {
          this.code = this.lastRunnedCode;

          return;
        }

        switch (this.language) {
          case "python":
            this.code = python;
            break;
          case "javascript":
            this.code = javascript;
            break;
          case "php":
            this.code = php;
            break;
          case "objective-c":
            this.code = c;
            break;
          case "cpp":
            this.code = cpp;
            break;
          case "go":
            this.code = go;
            break;
          case "java":
            this.code = java;
            break;
        }
      }
    },

    /**
      When user clicks on run button
    */
    async run() {
      this.isRunning = true;

      // Validating code, inputs, output
      if (!this.code) {
        this.$toasted.show("Please, Write at at least line of code", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
        this.isRunning = false;

        return;
      } else if (!this.inputs) {
        this.$toasted.show("Please provide inputs (STDIN)", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
        this.isRunning = false;

        return;
      } else if (!this.output) {
        this.$toasted.show("Please provide output (STDOUT)", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
        this.isRunning = false;

        return;
      }

      // Save code, and language
      this.lastRunnedCode = this.code;
      this.lastRunnedLanguage = this.language;

      try {
        let response = await axios.post(
          `/api/v1/coding-sessions/run/?session_id=${this.sessionId}`,
          {
            code: this.code,
            language: this.language,
            inputs: this.inputs,
            output: this.output,
          },
          {
            headers: {
              "x-access-token": this.accessToken,
            },
          }
        );

        response = response.data;

        if (response.is_expired || response.isSubmitted) {
          this.isSubmitted = true;

          return;
        }

        if (response.data.rce.matches) {
          this.$toasted.show("Program work according to expected output", {
            theme: "toasted-primary",
            type: "success",
            position: "bottom-right",
            duration: 3000,
          });
          this.err = "Error will appear here..";
        } else if (response.data.rce.hasError) {
          this.err = response.data.rce.errorMessage;

          this.$toasted.show("Program has errors, See Debugger tab ", {
            theme: "toasted-primary",
            type: "error",
            position: "bottom-right",
            duration: 3000,
          });
        } else if (
          !response.data.rce.matches &&
          !response.data.rce.outOfResources
        ) {
          this.err = response.data.rce.message;
          this.$toasted.show("Program not work according to expected output", {
            theme: "toasted-primary",
            type: "error",
            position: "bottom-right",
            duration: 3000,
          });
        } else if (response.data.rce.outOfResources) {
          this.err = "Program terminated due to out of time and resources.";
        }
      } catch (e) {
        this.$toasted.show("Something went wrong, Please re-run your code", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      }

      this.isRunning = false;
    },

    /**
      When user clicks on submit button
    */
    async submit() {
      try {
        if (!this.code)
          return this.$toasted.show("Please, Write at at least line of code", {
            theme: "toasted-primary",
            type: "error",
            position: "bottom-right",
            duration: 5000,
          });

        this.isSubmitting = true;
        let submission = await axios.post(
          `/api/v1/coding-sessions/submit/?session_id=${this.sessionId}`,
          {
            code: this.code,
            language: this.language,
          },
          {
            headers: {
              "x-access-token": this.accessToken,
            },
          }
        );
        submission = submission.data;

        if (submission.is_submitted) {
          this.isSubmitting = false;
          this.isSubmitted = true;
          this.$toasted.show("Your code has submitted :)", {
            theme: "toasted-primary",
            type: "success",
            position: "bottom-right",
            duration: 5000,
          });
        }
      } catch (e) {
        this.isSubmitting = false;
        this.isSubmitted = false;
        this.$toasted.show("Something went wrong, Please re-submit your code", {
          theme: "toasted-primary",
          type: "error",
          position: "bottom-right",
          duration: 5000,
        });
      }
    },
  },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=JetBrains+Mono&display=swap");

#inputs,
#output,
#debugger {
  font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono",
    "Courier New", monospace;
  font-size: 13px;
}

.loader {
  width: 100%;
  height: 100%;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: grid;
  place-items: center;
  background: #fff;
}

h1,
h2,
h3,
h4,
h5 {
  color: #02203c;
}

p {
  color: #02203c;
  font-size: 16px;
}

pre {
  color: #02203c;
  background-color: #fff;
  box-shadow: 0 2px 4px rgb(50 50 93 / 10%);
  display: block;
  padding: 8px;
  border-radius: 4px;
  font-size: 13px;
}

code {
  color: #24405f;
  background: #fff;
  padding: 4px;
  border-radius: 2px;
}
</style>
